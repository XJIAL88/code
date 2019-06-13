<template>
    <div class="prize">
        <el-form :model="form" ref="form" label-width="100px" :rules="rules">
            <el-row>
                <el-col :span="12">
                    <el-form-item label="选择活动" prop="activityId">
                        <el-select
                            v-model="form.activityId"
                            placeholder="请选择"
                            @change="changeActivity"
                            :disabled="!!$route.query.id"
                        >
                            <el-option
                                v-for="item in activityList"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="选择类型" prop="categoryId">
                        <el-select
                            v-model="form.categoryId"
                            placeholder="请选择"
                            @change="changeCategory"
                            :disabled="!!$route.query.id"
                        >
                            <el-option
                                v-for="item in categoryList"
                                :key="item.categoryId"
                                :label="item.category"
                                :value="item.categoryId"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="选择资源" prop="resourceId">
                        <el-select
                            v-model="form.resourceId"
                            placeholder="请选择"
                            @change="changeResource"
                            :disabled="!!$route.query.id"
                        >
                            <template v-for="item in resourceList">
                                <el-option
                                    v-if="item.categoryId == form.categoryId"
                                    :key="item.resourceId"
                                    :label="item.resourceName"
                                    :value="item.resourceId"
                                ></el-option>
                            </template>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="资源数量" prop="resourceNumber">
                        <el-input v-model="form.resourceNumber" placeholder="输入单个该奖品需要的资源数量"></el-input>
                    </el-form-item>
                    <el-form-item label="奖品数量" prop="awardNumber">
                        <el-input v-model="form.awardNumber" placeholder="输入该奖品的总数"></el-input>
                    </el-form-item>
                </el-col>

                <el-col :span="12">
                    <el-form-item label="奖品名称" prop="name">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                    <el-form-item label="奖品图片" prop="image">
                        <el-upload
                            class="avatar-uploader"
                            list-type="picture-card"
                            :data="{fileName:'file', fileType:0}"
                            :action="upload.action"
                            :show-file-list="false"
                            :on-success="handleAvatarSuccess"
                            :on-remove="removeImg"
                        >
                            <img v-if="form.image" :src="upload.domain+form.image" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
    </div>
</template>

<script>
import api from "@/api";
import { API_CONFIG, UPLOAD_DOMAIN } from "@/api/config.js";
import { getUserId, getUserToken } from "@/utils";
export default {
    data() {
        let checkResourceNumber = (rule, value, callback) => {
            if (parseFloat(value) > this.totalNumber) {
                callback(new Error("资源数量不能大于剩余的资源数量"));
            } else if (parseFloat(value) < 0.1) {
                callback(new Error("资源数量不能小于剩余0.1"));
            } else {
                callback();
            }
        };
        let checkAwardNumber = (rule, value, callback) => {
            if (
                parseInt(value) * parseFloat(this.form.resourceNumber) >
                this.totalNumber
            ) {
                callback(
                    new Error("奖品数量不能大于剩余的资源数量除以资源数量")
                );
            } else {
                callback();
            }
        };
        return {
            form: {
                activityId: "",
                name: "",
                categoryId: "",
                categoryName: "",
                resourceId: "",
                resourceNumber: "",
                awardNumber: "",
                image: ""
            },
            rules: {
                activityId: [
                    {
                        required: true,
                        message: "请选择活动",
                        trigger: "blur"
                    }
                ],
                categoryId: [
                    {
                        required: true,
                        message: "请选择类型",
                        trigger: "blur"
                    }
                ],
                resourceId: [
                    {
                        required: true,
                        message: "请选择资源",
                        trigger: "blur"
                    }
                ],
                resourceNumber: [
                    {
                        required: true,
                        message: "请输入资源数量",
                        trigger: "blur"
                    },
                    { validator: checkResourceNumber, trigger: "blur" }
                ],
                awardNumber: [
                    {
                        required: true,
                        message: "请输入奖品数量",
                        trigger: "blur"
                    },
                    { validator: checkAwardNumber, trigger: "blur" }
                ],
                name: [
                    {
                        required: true,
                        message: "请输入奖品名称",
                        trigger: "blur"
                    }
                ],
                image: [
                    {
                        required: true,
                        message: "请上传奖品图片",
                        trigger: "blur"
                    }
                ]
            },
            activityList: [],
            resourceList: [],
            categoryList: [],
            totalNumber: 0,
            upload: {
                action: `${
                    API_CONFIG.baseURL
                }uploadFileForm?adminId=${getUserId()}&adminToken=${getUserToken()}`,
                domain: UPLOAD_DOMAIN
            }
        };
    },
    mounted() {
        this.getActivityList();
        this.$nextTick(this.renderBreadCrumbAction);
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
                        确定
                    </el-button>,
                    <el-button size="medium" onClick={this.goBack}>
                        取消
                    </el-button>
                ]);
            });
        },
        getActivityList() {
            api.post("activityGetlist", { status: [2, 3, 4] }).then(res => {
                this.activityList = res.data.dataList;
                if (this.$route.query.id) {
                    this.getDetail();
                }
            });
        },
        getDetail() {
            api.post("activityAwardGet", { id: this.$route.query.id }).then(
                res => {
                    this.form = res.data;
                    this.changeActivity(this.form.activityId);
                }
            );
        },
        submitForm() {
            this.$refs.form.validate(valid => {
                if (valid) {
                    if (this.$route.query.id) {
                        api.post("activityAwardUpdate", {
                            id: this.$route.query.id,
                            ...this.form
                        }).then(res => {
                            this.$router.back();
                        });
                    } else {
                        api.post("activityAwardInsert", this.form).then(res => {
                            this.$router.back();
                        });
                    }
                } else {
                    return false;
                }
            });
        },
        goBack() {
            this.$router.back();
        },
        changeActivity(id) {
            this.activityList.forEach(item => {
                if (item.id == id) {
                    this.activityResourceDetail(item.number);
                }
            });
        },
        /**
         * 资源列表
         */
        activityResourceDetail(number) {
            api.post("activityResourceDetail", {
                orderNumber: number
            }).then(res => {
                this.resourceList = res.data.dataList;
                this.categoryList = [];
                let arr = [];
                res.data.dataList.forEach(item => {
                    if (arr.indexOf(item.categoryId) < 0) {
                        arr.push(item.categoryId);
                        this.categoryList.push({
                            categoryId: item.categoryId,
                            category: item.category
                        });
                    }
                });
                if (this.$route.query.id) {
                    this.changeResource(this.form.resourceId);
                }
            });
        },
        changeCategory(id) {
            this.categoryList.forEach(item => {
                if (item.categoryId == id) {
                    this.form.categoryName = item.category;
                }
            });
            this.form.resourceId = "";
        },
        changeResource(id) {
            this.resourceList.forEach(item => {
                if (item.resourceId == id) {
                    this.totalNumber = item.number;
                }
            });
        },
        handleAvatarSuccess(res, file, fileList) {
            this.form.image = res.data;
        },
        removeImg() {}
    }
};
</script>

<style lang="scss" scoped>
.prize {
    padding: 10px 0;
    /deep/ .el-form-item__content {
        width: 400px;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409eff;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 118px;
        height: 118px;
        line-height: 118px;
        text-align: center;
    }
    .avatar {
        width: 118px;
        height: 118px;
        display: block;
    }
    /deep/ .el-upload--picture-card {
        width: 120px;
        height: 120px;
    }
}
</style>
