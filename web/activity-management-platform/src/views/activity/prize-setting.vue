<template>
    <div class="prize-setting">
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
                    <el-form-item label="活动可用奖品：">
                        <span></span>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="活动类型：">
                        <span>开门抽奖</span>
                    </el-form-item>
                    <el-form-item label="新人专享：">
                        <el-switch v-model="form.new" disabled></el-switch>（新人：注册30天内的用户）
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-table :data="prizeList" border style="width:721px;margin-left:20px">
            <el-table-column prop="categoryName" label="资源类型" width="180" align="center"></el-table-column>
            <el-table-column prop="name" label="奖品名称" width="180" align="center"></el-table-column>
            <el-table-column prop="awardNumber" label="剩余" width="180" align="center">
                <template slot-scope="scope">
                    <span>{{scope.row.awardNumber-scope.row.usedNumber}}</span>
                </template>
            </el-table-column>
            <el-table-column prop="usedNumber" label="已发放" width="180" align="center"></el-table-column>
        </el-table>
        <div style="margin:20px" ref="content">奖品设置：</div>
        <div style="margin-left:20px">
            <div>
                <el-checkbox-group v-model="checkList">
                    <el-checkbox
                        :label="item.level"
                        v-for="item in levelList"
                        :key="item.level"
                    >{{item.name}}</el-checkbox>-->
                    <label class="el-checkbox">的奖品全部发放完时，活动将自动下线</label>
                </el-checkbox-group>
            </div>
            <div style="margin-top:15px;">
                <el-table :data="list" border class="setting-table" :style="tableStyle">
                    <el-table-column label="奖品级别" prop="name" width="180" align="center" fixed></el-table-column>
                    <el-table-column label="奖品类型" width="180" align="center">
                        <template slot-scope="scope">
                            <el-select
                                v-model="scope.row.categoryId"
                                clearable
                                placeholder="请选择"
                                @change="changeCategory(scope.row)"
                            >
                                <el-option
                                    v-for="item in categoryList"
                                    :key="item.categoryId"
                                    :label="item.categoryName"
                                    :value="item.categoryId"
                                ></el-option>
                            </el-select>
                        </template>
                    </el-table-column>
                    <el-table-column label="奖品" width="180" align="center">
                        <template slot-scope="scope">
                            <el-select
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
                            </el-select>
                        </template>
                    </el-table-column>
                    <el-table-column label="发放数量" width="180" align="center">
                        <template slot-scope="scope">
                            <el-input v-model="scope.row.number" placeholder="不限" type="number"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column label="每日上限（数量）" width="180" align="center">
                        <template slot-scope="scope">
                            <el-form class="table-form">
                                <el-form-item label="全部:" label-width="55px">
                                    <el-input
                                        v-model="scope.row.allDailyNumber"
                                        placeholder="不限"
                                        type="number"
                                        size="small"
                                        min="0"
                                    ></el-input>
                                </el-form-item>
                                <el-form-item label="每人:" label-width="55px">
                                    <el-input
                                        v-model="scope.row.personalDailyNumber"
                                        placeholder="不限"
                                        type="number"
                                        size="small"
                                        min="0"
                                    ></el-input>
                                </el-form-item>
                            </el-form>
                        </template>
                    </el-table-column>
                    <template v-for="(col,colIndex) in timeList">
                        <el-table-column :key="colIndex" align="center" width="190">
                            <template slot="header" slot-scope="scope">
                                <el-button type="text" @click="delCol(colIndex)">删除时段</el-button>
                                <time-picker :time.sync="col.time"></time-picker>
                            </template>
                            <template slot-scope="scope">
                                <el-input
                                    v-model="scope.row.rate[colIndex]"
                                    size="mini"
                                    placeholder="请输入中奖几率"
                                />
                            </template>
                        </el-table-column>
                    </template>
                    <el-table-column label="操作" width="180" align="center">
                        <template slot-scope="scope">
                            <el-button type="text" @click="delRow(scope)">删除奖品</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div style="margin-top:15px;padding-bottom:50px;">
                <el-button @click="addCol">添加时段规则</el-button>
                <el-button @click="addRow">添加奖品</el-button>
            </div>
        </div>
    </div>
</template>

<script>
import api from "@/api";
import timePicker from "./component/time-picker";
export default {
    components: {
        timePicker
    },
    data() {
        return {
            // search: "",
            form: {},
            prizeList: [],
            categoryList: [],
            checkList: [],
            levelList: [
                {
                    name: "一等奖",
                    level: "1"
                },
                {
                    name: "二等奖",
                    level: "2"
                },
                {
                    name: "三等奖",
                    level: "3"
                },
                {
                    name: "四等奖",
                    level: "4"
                },
                {
                    name: "五等奖",
                    level: "5"
                }
            ],
            list: [
                {
                    name: "一等奖",
                    level: "1",
                    categoryId: "",
                    category: "",
                    activityId: this.$route.query.id,
                    awardId: "",
                    number: "",
                    resourceNumber: "",
                    allDailyNumber: "",
                    personalDailyNumber: "",
                    rate: [""]
                },
                {
                    name: "二等奖",
                    level: "2",
                    categoryId: "",
                    category: "",
                    activityId: this.$route.query.id,
                    awardId: "",
                    number: "",
                    resourceNumber: "",
                    allDailyNumber: "",
                    personalDailyNumber: "",
                    rate: [""]
                },
                {
                    name: "三等奖",
                    level: "3",
                    categoryId: "",
                    category: "",
                    activityId: this.$route.query.id,
                    awardId: "",
                    number: "",
                    resourceNumber: "",
                    allDailyNumber: "",
                    personalDailyNumber: "",
                    rate: [""]
                },
                {
                    name: "四等奖",
                    level: "4",
                    categoryId: "",
                    category: "",
                    activityId: this.$route.query.id,
                    awardId: "",
                    number: "",
                    resourceNumber: "",
                    allDailyNumber: "",
                    personalDailyNumber: "",
                    rate: [""]
                },
                {
                    name: "五等奖",
                    level: "5",
                    categoryId: "",
                    category: "",
                    activityId: this.$route.query.id,
                    awardId: "",
                    number: "",
                    resourceNumber: "",
                    allDailyNumber: "",
                    personalDailyNumber: "",
                    rate: [""]
                }
            ],
            timeList: [{ time: "00:00-23:59" }],
            tableStyle: {
                width: "",
                "max-width": ""
            }
        };
    },
    created() {
        this.getDetail();
        this.getPrizeList();
        this.$nextTick(this.renderBreadCrumbAction);
        this.$nextTick(() => {
            this.tableStyle = {
                width: 1080 + this.timeList.length * 190 + 1 + "px",
                "max-width": this.$refs.content.offsetWidth - 150 + "px"
            };
        });
    },
    methods: {
        renderBreadCrumbAction() {
            this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
                return h("div", {}, [
                    <el-button
                        size="medium"
                        type="primary"
                        onClick={this.submitForm}
                    >
                        发布
                    </el-button>,
                    <el-button size="medium" onClick={this.goBack}>
                        取消
                    </el-button>
                ]);
            });
        },
        /**
         * 活动
         */
        getDetail() {
            api.post("activityGet", { id: this.$route.query.id }).then(res => {
                this.form = res.data;
                if (res.data.award.length > 0) {
                    this.list = res.data.award;
                    this.timeList = [];
                    res.data.time.forEach(item => {
                        this.timeList.push({ time: item });
                    });
                    this.levelList = [];
                    res.data.award.forEach(item => {
                        let obj = {
                            name: item.name,
                            level: item.level + ""
                        };
                        this.levelList.push(obj);
                    });
                    this.checkList = this.form.level.split(",");
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
                    this.categoryList = [];
                    let arr = [];
                    res.data.dataList[0].award.forEach(item => {
                        if (!arr.includes(item.categoryId)) {
                            arr.push(item.categoryId);
                            this.categoryList.push({
                                categoryId: item.categoryId,
                                categoryName: item.categoryName
                            });
                        }
                    });
                }
            });
        },
        submitForm() {
            if (this.timeList.length == 0) {
                this.showTips("请添加时段");
                return;
            }
            if (this.list.length == 0) {
                this.showTips("请添加奖品");
                return;
            }
            let time = [];
            let flag = false;
            this.timeList.forEach(item => {
                if (!item.time) {
                    this.showTips("请输入时段");
                    flag = true;
                    return;
                }
                time.push(item.time);
            });
            if (flag) return;
            this.list.forEach(item => {
                let checkResult = this.check(item);
                if (checkResult) {
                    this.showTips(checkResult);
                    flag = true;
                    return;
                }
            });
            if (flag) return;
            let params = {
                id: this.$route.query.id,
                level: this.checkList,
                time: time,
                resourceArray: this.list
            };
            api.post("activityUpdate", params).then(res => {
                this.$router.back();
            });
        },
        showTips(text) {
            this.$alert(text, "提示", {
                type: "warning",
                confirmButtonText: "确定",
                callback: action => {}
            });
        },
        check(item) {
            if (!item.categoryId) {
                return "请选择奖品类型";
            }
            if (!item.awardId) {
                return "请选择奖品";
            }
            if (!item.number) {
                return "请输入发放数量";
            }
            let str1 = "";
            this.prizeList.forEach(prize => {
                if (item.awardId == prize.id) {
                    item.resourceNumber = prize.resourceNumber;
                    if (item.number > prize.awardNumber) {
                        str1 = "发放数量不能大于奖品数量";
                    }
                }
            });
            if (str1) {
                return str1;
            }
            if (
                item.allDailyNumber.indexOf("-") >= 0 ||
                item.personalDailyNumber.indexOf("-") >= 0
            ) {
                return "每日上限不能为负";
            }
            // if (!item.allDailyNumber || !item.personalDailyNumber) {
            //     return "请输入每日上限";
            // }
            let str = "";
            item.rate.forEach(rate => {
                if (!rate) {
                    str = "请输入中奖概率";
                }
            });
            return str;
        },
        goBack() {
            this.$router.back();
        },
        changeResource(row) {
            this.prizeList.forEach(item => {
                if (item.id == row.awardId) {
                    row.number = item.awardNumber;
                    row.resourceNumber = item.resourceNumber;
                }
            });
        },
        changeCategory(row) {
            if (row.categoryId) {
                this.categoryList.forEach(item => {
                    if (item.categoryId == row.categoryId) {
                        row.category = item.categoryName;
                    }
                });
            }
            row.awardId = "";
        },
        /**
         * 添加奖品
         */
        addRow() {
            let arr = [];
            this.timeList.forEach(item => {
                arr.push("");
            });
            let obj = {
                name: this.formatName(this.list.length + 1),
                level: this.list.length + 1 + "",
                categoryId: "",
                category: "",
                activityId: this.$route.query.id,
                awardId: "",
                number: "",
                resourceNumber: "",
                allDailyNumber: "",
                personalDailyNumber: "",
                rate: arr
            };
            this.list.push(obj);
            this.levelList.push({
                name: this.formatName(this.levelList.length + 1),
                level: this.levelList.length + 1 + ""
            });
        },
        delRow(scope) {
            this.list.splice(scope.$index, 1);
            this.levelList.splice(scope.$index, 1);
        },
        /**
         * 添加时段
         */
        addCol() {
            this.timeList.push({ time: "" });
            this.list.forEach(item => {
                item.rate.push("");
            });
            this.$nextTick(() => {
                this.tableStyle = {
                    width: 1080 + this.timeList.length * 190 + 1 + "px",
                    "max-width": this.$refs.content.offsetWidth - 150 + "px"
                };
            });
        },
        delCol(index) {
            this.timeList.splice(index, 1);
            this.list.forEach(item => {
                item.rate.splice(index, 1);
            });
            this.$nextTick(() => {
                this.tableStyle = {
                    width: 1080 + this.timeList.length * 190 + 1 + "px",
                    "max-width": this.$refs.content.offsetWidth - 150 + "px"
                };
            });
        },
        formatName(level) {
            return this.SectionToChinese(level) + "等奖";
        },
        SectionToChinese(section) {
            var chnNumChar = [
                "零",
                "一",
                "二",
                "三",
                "四",
                "五",
                "六",
                "七",
                "八",
                "九"
            ];
            var chnUnitSection = ["", "万", "亿", "万亿", "亿亿"];
            var chnUnitChar = ["", "十", "百", "千"];
            var strIns = "",
                chnStr = "";
            var unitPos = 0;
            var zero = true;
            while (section > 0) {
                var v = section % 10;
                if (v === 0) {
                    if (!zero) {
                        zero = true;
                        chnStr = chnNumChar[v] + chnStr;
                    }
                } else {
                    zero = false;
                    strIns = chnNumChar[v];
                    strIns += chnUnitChar[unitPos];
                    chnStr = strIns + chnStr;
                }
                unitPos++;
                section = Math.floor(section / 10);
            }
            return chnStr;
        }
    }
};
</script>

<style lang="scss" scoped>
.prize-setting {
    padding: 10px 15px;
    /deep/ thead th {
        background: #eee;
    }
    .table-form {
        .el-form-item {
            margin-bottom: 0;
        }
    }
    .setting-table {
        max-width: 90%;
    }
    // @media screen and (max-width: 1366px) {
    //     .setting-table {
    //         width: 1085px;
    //     }
    // }
    // @media screen and (max-width: 1440px) {
    //     .setting-table {
    //         width: 1163px;
    //     }
    // }
    // @media screen and (max-width: 1600px) {
    //     .setting-table {
    //         width: 1323px;
    //     }
    // }
}
</style>

