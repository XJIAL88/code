<template>
    <div class="activity-edit">
        <el-form :model="form" ref="form" label-width="100px" :rules="rules" v-if="isFirst">
            <el-row class="form-width">
                <el-col :span="12">
                    <el-form-item label="活动名称" prop="name">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="活动时间" prop="startAt">
                        <FormDatetimeRange :start-at.sync="form.startAt" :end-at.sync="form.endAt"></FormDatetimeRange>
                    </el-form-item>
                    <el-form-item label="抽奖次数" prop="status" class="draw-count">
                        <el-input v-model="form.numberTotal" placeholder="无限">
                            <template slot="prepend">每用户共可参加</template>
                            <template slot="append">次</template>
                        </el-input>
                        <el-input
                            v-model="form.numberDaily"
                            style="margin-top:15px"
                            placeholder="无限"
                        >
                            <template slot="prepend">每用户每天可参加</template>
                            <template slot="append">次</template>
                        </el-input>
                    </el-form-item>
                </el-col>

                <el-col :span="12">
                    <el-form-item label="活动类型" prop="status">
                        <el-select v-model="form.type" placeholder="请选择">
                            <el-option
                                v-for="item in typeList"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <!-- <el-form-item label="活动范围" prop="groupId">
                        <el-select v-model="form.groupId" placeholder="请选择" @change="changeGroup">
                            <el-option
                                v-for="item in groupList"
                                :key="item.id"
                                :label="item.title"
                                :value="item.id"
                            ></el-option>
                        </el-select>
                    </el-form-item>-->
                    <el-form-item label="新人专享" prop="new">
                        <el-switch v-model="form.new"></el-switch>（新人：注册
                        <el-button type="text" @click="updateDay">{{form.newDaylimit}}</el-button>天内的用户）
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="16">
                    <el-form-item label="活动规则">
                        <el-input v-model="form.rule" type="textarea" :rows="4"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <div v-if="!isFirst">
            <div style="padding:10px 30px;font-size:16px;">申请资源：</div>
            <el-table :data="list" border style="width:901px;margin-left:30px">
                <el-table-column prop="categoryId" label="资源类型" width="180" align="center">
                    <template slot-scope="scope">
                        <el-cascader
                            clearable
                            :options="categoryList"
                            v-model="scope.row.categoryId"
                            @change="changeCategory(scope.row)"
                            :props="props"
                            :show-all-levels="false"
                        ></el-cascader>
                    </template>
                </el-table-column>
                <el-table-column prop="name" label="资源名称" width="180" align="center">
                    <template slot-scope="scope">
                        <el-select
                            v-model="scope.row.resourceId"
                            clearable
                            placeholder="请选择"
                            @change="changeResource(scope.row)"
                        >
                            <el-option
                                v-for="item in scope.row.resourceList"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id"
                            ></el-option>
                        </el-select>
                    </template>
                </el-table-column>
                <el-table-column prop="canApply" label="可申请总数量" width="180" align="center"></el-table-column>
                <el-table-column prop="number" label="申请数量" width="180" align="center">
                    <template slot-scope="scope">
                        <el-input v-model="scope.row.number" placeholder="请输入" type="number"></el-input>
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="180" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" @click="delItem(scope.$index)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-button
                type="primary"
                icon="el-icon-plus"
                style="margin-left:30px;margin-top:15px"
                @click="addItem"
            >添加资源</el-button>
        </div>
    </div>
</template>

<script>
import api from "@/api";
import {
    sessionStorageSet,
    sessionStorageGet,
    sessionStorageRemove
} from "@/utils";
export default {
    data() {
        return {
            form: {
                name: "",
                type: 1,
                startAt: "",
                endAt: "",
                numberTotal: "",
                numberDaily: "",
                new: false,
                rule: "",
                // groupId: "",
                // gorupTitle: "",
                newDaylimit: 30,
                resource: []
            },
            rules: {
                name: [
                    {
                        required: true,
                        message: "请输入活动名称",
                        trigger: "blur"
                    }
                ],
                startAt: [
                    {
                        required: true,
                        message: "请输入活动时间",
                        trigger: "blur"
                    }
                ],
                groupId: [
                    {
                        required: true,
                        message: "请输入活动范围",
                        trigger: "blur"
                    }
                ]
            },
            props: {
                label: "title",
                value: "id",
                children: "son"
            },
            typeList: [
                {
                    value: 1,
                    label: "开门抽奖"
                }
            ],
            groupList: [],
            categoryList: [],
            isFirst: true,
            list: []
        };
    },
    mounted() {
        this.initInfo();
        // this.getGroupList();
        this.getCategoryList();
    },
    methods: {
        renderFirst() {
            this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
                return h("div", {}, [
                    <el-button size="medium" type="primary" onClick={this.next}>
                        下一步
                    </el-button>,
                    <el-button size="medium" onClick={this.goBack}>
                        取消
                    </el-button>
                ]);
            });
        },
        renderBreadCrumbAction() {
            this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
                return h("div", {}, [
                    <el-button
                        size="medium"
                        type="primary"
                        onClick={this.submitForm}
                    >
                        提交审批
                    </el-button>,
                    <el-button size="medium" onClick={this.goBack}>
                        取消
                    </el-button>
                ]);
            });
        },
        initInfo() {
            let form = sessionStorageGet("activityInfo");
            if (form) {
                this.form = form;
                this.isFirst = false;
                this.$nextTick(this.renderBreadCrumbAction);
            } else {
                this.$nextTick(this.renderFirst);
            }
        },
        getGroupList() {
            api.post("activityGroupGetList").then(res => {
                this.groupList = res.data.dataList;
            });
        },
        getCategoryList() {
            api.post("resourceCategoryList").then(res => {
                this.categoryList = res.data.dataList;
            });
        },
        updateDay() {
            this.$prompt("请输入天数", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                inputPattern: /^[1-9][0-9]{0,14}$/,
                inputErrorMessage: "请输入整数"
            })
                .then(({ value }) => {
                    this.form.newDaylimit = value;
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: "取消输入"
                    });
                });
        },
        next() {
            this.$refs.form.validate(valid => {
                if (valid) {
                    sessionStorageSet("activityInfo", this.form);
                    this.isFirst = false;
                    this.$nextTick(this.renderBreadCrumbAction);
                } else {
                    return false;
                }
            });
        },
        submitForm() {
            let resource = [];
            if (this.list.length == 0) {
                this.showTips("请添加资源");
                return;
            }
            let flag = false;
            this.list.forEach(item => {
                let checkResult = this.check(item);
                if (checkResult) {
                    this.showTips(checkResult);
                    flag = true;
                    return;
                }
                let obj = {
                    resourceId: item.resourceId,
                    resourceName: item.resourceName,
                    categoryId: item.categoryId[item.categoryId.length - 1],
                    category: item.category,
                    grantType: 1,
                    number: item.number
                };
                resource.push(obj);
            });
            if (flag) return;
            this.form.resource = resource;
            api.post("activityInsert", this.form).then(res => {
                this.goBack();
            });
        },
        goBack() {
            sessionStorageRemove("activityInfo");
            this.$router.back();
        },
        changeGroup(val) {
            this.groupList.forEach(item => {
                if (item.id == val) {
                    this.form.gorupTitle = item.title;
                }
            });
        },
        addItem() {
            let obj = {
                categoryId: [],
                category: "",
                number: "",
                resourceList: [],
                resourceId: "",
                resourceName: "",
                grantType: 1
            };
            this.list.push(obj);
        },
        delItem(index) {
            this.list.splice(index, 1);
        },
        changeCategory(row) {
            let val = row.categoryId;
            let length = val.length;
            if (length) {
                for (let i = 0; i < val.length; i++) {
                    this.categoryList.forEach(item => {
                        if (item.id == val[0]) {
                            if (length == 1) {
                                row.category = item.title;
                            } else {
                                item.son.forEach(ele => {
                                    if (ele.id == val[1]) {
                                        if (length == 2) {
                                            row.category = ele.title;
                                        } else {
                                            ele.son.forEach(info => {
                                                if (info.id == val[2]) {
                                                    row.category = info.title;
                                                }
                                            });
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            } else {
                row.category = "";
            }
            row.resourceId = "";
            row.resourceName = "";
            row.canApply = null;
            row.number = "";
            this.getResurceList(row);
        },
        changeResource(row) {
            if (row.resourceId) {
                row.resourceList.forEach(item => {
                    if (item.id == row.resourceId) {
                        row.resourceName = item.name;
                        row.canApply = item.quantity - item.used - item.freeze;
                    }
                });
            }
        },
        getResurceList(row) {
            let params = {
                category: "",
                categoryId: row.categoryId[row.categoryId.length - 1],
                platform: "",
                name: "",
                status: 1,
                grantType: "",
                callType: "",
                pageSize: 1000,
                pageNumber: 1
            };
            api.post("resourceGetList", params, { loading: false }).then(
                res => {
                    row.resourceList = res.data.dataList;
                }
            );
        },
        check(item) {
            if (!item.category) {
                return "请选择资源类型";
            }
            if (!item.resourceId) {
                return "请选择资源";
            }
            if (!item.number) {
                return "请输入申请数量";
            }
            if (parseInt(item.number) > parseInt(item.canApply)) {
                return "申请数量不能大于可申请数量";
            }
            return "";
        },
        showTips(text) {
            this.$alert(text, "提示", {
                type: "warning",
                confirmButtonText: "确定",
                callback: action => {}
            });
        }
    }
};
</script>

<style lang="scss" scoped>
.activity-edit {
    padding: 10px 0;
    /deep/ .el-input-group__append,
    /deep/ .el-input-group__prepend {
        background: #fff;
    }
    .draw-count {
        /deep/ .el-input__inner {
            // border-left: 0;
            // border-right: 0;
        }
    }
    .form-width {
        /deep/ .el-form-item__content {
            width: 400px;
        }
    }
}
</style>

