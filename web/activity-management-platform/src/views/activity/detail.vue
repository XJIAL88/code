<template>
    <div>
        <el-form :model="form" ref="form" label-width="100px">
            <el-row>
                <el-col :span="12">
                    <el-form-item label="活动名称：">
                        <span>{{form.name}}</span>
                    </el-form-item>
                    <el-form-item label="活动时间：">
                        <span>{{form.startAt|datetime}}~{{form.endAt|datetime}}</span>
                    </el-form-item>
                    <el-form-item label="抽奖次数：">
                        <span>每用户共可参加{{form.numberTotal?form.numberTotal:'无限'}}次&nbsp;&nbsp;每用户每天可参加{{form.numberDaily?form.numberDaily:'无限'}}次</span>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="活动类型：">
                        <span>开门抽奖</span>
                    </el-form-item>
                    <el-form-item label="新人专享：">
                        <el-switch v-model="!!form.new" disabled></el-switch>
                        （新人：注册{{form.newDaylimit}}天内的用户）
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <div style="margin:20px" v-if="form.award.length>0" ref="content">奖品设置：</div>
        <div style="margin-left:20px" v-if="form.award.length>0">
            <div>
                <el-checkbox-group v-model="checkList">
                    <el-checkbox
                        :label="item.level"
                        v-for="item in levelList"
                        :key="item.level"
                        disabled
                    >{{item.name}}</el-checkbox>-->
                    <label class="el-checkbox">的奖品全部发放完时，活动将自动下线</label>
                </el-checkbox-group>
            </div>
            <div style="margin-top:15px">
                <el-table :data="form.award" border :style="tableStyle">
                    <el-table-column label="奖品级别" prop="name" width="180" align="center"></el-table-column>
                    <el-table-column label="奖品类型" width="180" align="center">
                        <template slot-scope="scope">
                            <span>{{scope.row.category}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="奖品" width="180" align="center">
                        <template slot-scope="scope">
                            <template v-for="item in prizeList">
                                <span
                                    v-if="scope.row.awardId == item.id"
                                    :key="item.id"
                                >{{item.name}}</span>
                            </template>
                            <!-- <el-select
                                v-model="scope.row.awardId"
                                clearable
                                placeholder="请选择"
                                @change="changeResource(scope.row)"
                            >
                                <template v-for="item in prizeList">
                                    <el-option
                                        v-if="scope.row.categoryId == item.categoryId"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id"
                                    ></el-option>
                                </template>
                            </el-select>-->
                        </template>
                    </el-table-column>
                    <el-table-column label="发放数量" width="180" align="center">
                        <template slot-scope="scope">
                            <span>{{scope.row.number}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="每日上限（数量）" width="180" align="center">
                        <template slot-scope="scope">
                            <p>全部：{{scope.row.allDailyNumber?scope.row.allDailyNumber:'不限'}}</p>
                            <p>每人：{{scope.row.personalDailyNumber?scope.row.personalDailyNumber:'不限'}}</p>
                        </template>
                    </el-table-column>
                    <template v-for="(col,colIndex) in form.time">
                        <el-table-column :key="colIndex" :label="col" align="center" width="190">
                            <template slot-scope="scope">
                                <span>{{scope.row.rate[colIndex]}}</span>
                            </template>
                        </el-table-column>
                    </template>
                </el-table>
            </div>
        </div>

        <div style="margin:20px" v-if="form.changeLog.length>0">操作日志：</div>
        <div style="margin-top:15px" v-if="form.changeLog.length>0">
            <el-table :data="form.changeLog">
                <el-table-column label="操作时间" align="center">
                    <template slot-scope="scope">
                        <span>{{scope.row.create_at|datetime}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作人" prop="operator" align="center"></el-table-column>
                <el-table-column label="奖品名称" prop="name" align="center"></el-table-column>
                <el-table-column label="变更字段" prop="field" align="center"></el-table-column>
                <el-table-column label="变更前" prop="before" align="center"></el-table-column>
                <el-table-column label="变更后" prop="after" align="center"></el-table-column>
            </el-table>
        </div>
    </div>
</template>

<script>
import api from "@/api";
export default {
    data() {
        return {
            form: {},
            checkList: [],
            levelList: [],
            prizeList: [],
            tableStyle: {
                width: "",
                "max-width": ""
            }
        };
    },
    mounted() {
        this.getPrizeList();
        this.getDetail();
        this.$nextTick(this.renderBreadCrumbAction);
    },
    methods: {
        renderBreadCrumbAction() {
            this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
                if (
                    this.$route.query.status == 1 ||
                    this.$route.query.status == 6
                ) {
                    return h("div", {}, [
                        <el-button
                            size="medium"
                            type="primary"
                            onClick={this.goBack}
                        >
                            取消
                        </el-button>
                    ]);
                }
                return h("div", {}, [
                    <el-button size="medium" onClick={this.edit}>
                        编辑
                    </el-button>,
                    <el-button
                        size="medium"
                        type="primary"
                        onClick={this.goBack}
                    >
                        取消
                    </el-button>
                ]);
            });
        },
        edit() {
            this.$router.push({
                name: "activityPrizeSetting",
                query: {
                    id: this.$route.query.id,
                    number: this.$route.query.number
                }
            });
        },
        goBack() {
            this.$router.back();
        },
        getDetail() {
            api.post("activityGet", { id: this.$route.query.id }).then(res => {
                this.form = res.data;
                if (res.data.award.length > 0) {
                    this.form.award.forEach(item => {
                        let obj = {
                            name: item.name,
                            level: item.level + ""
                        };
                        this.levelList.push(obj);
                    });
                    this.form.level = this.form.level + "";
                    this.checkList = this.form.level.split(",");
                    this.$nextTick(() => {
                        this.tableStyle = {
                            width: 900 + this.form.time.length * 190 + 1 + "px",
                            "max-width":
                                this.$refs.content.offsetWidth - 150 + "px"
                        };
                    });
                }
            });
        },
        getPrizeList() {
            let params = {
                search: this.$route.query.number,
                pageSize: 1000,
                pageNumber: 1
            };
            api.post("activityAwardGetlist", params).then(res => {
                if (res.data.dataList.length > 0) {
                    this.prizeList = res.data.dataList[0].award;
                }
            });
        }
    }
};
</script>
