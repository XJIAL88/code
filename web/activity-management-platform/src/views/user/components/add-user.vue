<template>
    <div>
        <el-dialog
            :visible.sync="visible"
            :before-close="()=>{$emit('update:visible',false)}"
            width="800px"
        >
            <el-row slot="title" class="dialog-title">
                <el-row type="flex" align="middle" justify="space-between">
                    <h4 class="title">添加用户</h4>
                    <el-button class="save-button" type="text" @click="save">保存</el-button>
                </el-row>
            </el-row>
            <el-row class="dialog-body">
                <el-row class="import-content">
                    <el-row type="flex" align="middle" justify="center" class="import-input">
                        <span>账号添加：</span>
                        <el-col :span="14">
                            <el-input clearable v-model="newUser" placeholder="输入用户彩之云账号"></el-input>
                        </el-col>
                        <el-button class="button" type="text" @click="handleAddUserToList">添加</el-button>
                    </el-row>
                    <el-row type="flex" align="middle" justify="center" class="import-input">
                        <span>批量导入：</span>
                        <el-col :span="14">
                            <el-input placeholder :value="fileName"></el-input>
                        </el-col>
                        <el-upload
                            class="upload-demo"
                            accept=".csv"
                            :action="action + `?adminId=${adminId}&adminToken=${adminToken}`"
                            :data="{adminId,adminToken,fileName:'file'}"
                            :show-file-list="false"
                            :on-progress="handleUploadProgress"
                            :on-success="handleUploadSuccess"
                            :on-error="handleUploadError"
                        >
                            <el-button class="button" type="text">添加</el-button>
                        </el-upload>
                    </el-row>
                    <el-row type="flex" align="middle" justify="center">
                        <el-button type="primary" plain @click="exportUserList">下载模版</el-button>
                        <el-button type="primary" @click="handleImport">确认导入</el-button>
                    </el-row>
                </el-row>
                <el-row class="count">
                    共
                    <span style="color:#6699FF">{{userList.length}}</span> 条
                </el-row>
                <el-row class="export-content">
                    <el-row
                        class="export-item"
                        type="flex"
                        align="middle"
                        v-for="(user,userIndex) in userList"
                        :key="user.mobile"
                    >
                        <el-col :span="12" class="left">{{user.mobile}}</el-col>
                        <el-col :span="10" class="right">
                            <el-button
                                v-if="!user.mark"
                                type="text"
                                @click="handleRemoveUser(userIndex)"
                            >删除</el-button>
                            <span v-else class="disable">{{user.mark}}</span>
                        </el-col>
                    </el-row>
                </el-row>
            </el-row>
        </el-dialog>

        <copy-dialog :visible.sync="centerDialogVisible" :time="minute" :url="downloadUrl"></copy-dialog>
    </div>
</template>

<script>
import api from "@/api";
import { API_CONFIG } from "@/api/config";
import { getUserId, getUserToken } from "@/utils";
export default {
    name: "addUser",
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        groupId: {
            type: [String, Number],
            default: ""
        }
    },
    data() {
        return {
            adminId: getUserId(),
            adminToken: getUserToken(),
            action: API_CONFIG.baseURL + "activityExcelUserImport",
            newUser: "",
            fileName: "",
            userListFile: "",
            userList: [],
            centerDialogVisible: false,
            minute: "",
            downloadUrl: ""
        };
    },
    watch: {
        visible(val) {
            this.newUser = "";
            this.fileName = "";
            this.userList = [];
            this.userListFile = "";
        }
    },
    methods: {
        /**
         * 保存
         */
        save() {
            if (!this.userList.length) {
                this.$alert("请添加用户", {
                    type: "error"
                });
                return;
            }
            const mobile = [];
            this.userList.forEach(user => {
                if (!user.mark) {
                    mobile.push(user.mobile);
                }
            });
            api.post("traderActivityUserInsertMore", {
                mobile,
                activityUserGroupId: this.groupId
            }).then(res => {
                // this.dialogVisible = false;
                // const that = this;
                // this.$alert(res.data, {
                //     type: "success",
                //     callback() {
                //         that.loadData();
                //     }
                // });
                this.$emit("update:visible", false);
                this.$alert("保存成功", {
                    type: "success"
                });
            });
        },
        /**
         * 账号添加
         */
        handleAddUserToList() {
            api.post("activityUserInsertCheck", {
                mobile: this.newUser,
                activityUserGroupId: this.groupId
            }).then(res => {
                this.userList.push({
                    mobile: res.data.mobile,
                    mark: res.data.mark
                });
            });
        },
        handleUploadProgress() {
            this.fileName = "上传中...";
        },
        handleUploadSuccess(response, file, fileList) {
            if (response.code === 0) {
                this.fileName = file.name;
                this.userListFile = response.data;
            } else {
                this.fileName = "";
                if (response.message) {
                    this.$alert(`${response.message}`, "上传失败", {
                        type: "error"
                    });
                }
            }
        },
        handleUploadError() {
            this.$alert("上传失败，请重新上传", "服务器异常", {
                type: "error"
            });
            this.fileName = "";
        },
        /**
         * 下载模板
         */
        exportUserList() {
            api.post("activityExcelUser").then(res => {
                this.minute = res.data.time;
                this.downloadUrl = res.data.path;
                this.centerDialogVisible = true;
            });
        },
        /**
         * 确认导入
         */
        handleImport() {
            api.post("activityUserInsertMoreCheck", {
                file: this.userListFile,
                activityUserGroupUuid: this.groupId
            }).then(res => {
                res.data.forEach(item => {
                    if (this.userList.length) {
                        const isExist = this.userList.some(user => {
                            return item.mobile === user.mobile;
                        });
                        if (!isExist) this.userList.push(item);
                    } else {
                        this.userList.push(item);
                    }
                });
            });
        },
        handleRemoveUser() {
            this.userList.splice(index, 1);
        }
    }
};
</script>

<style lang="scss" scoped>
.dialog-title {
    .title {
        font-size: 16px;
        font-weight: bold;
    }
    .save-button {
        margin-right: 26px;
        padding: 0;
    }
}
.dialog-body {
    .import-content {
        .import-input {
            margin-bottom: 10px;
        }
        .button {
            margin-left: 10px;
        }
    }
    .count {
        padding: 5px 10px;
        margin: 10px 0;
        background: rgba(242, 242, 242, 1);
    }
    .export-content {
        padding: 20px 0;
        .export-item {
            padding: 5px;
            .left {
                text-align: center;
            }
            .right {
                text-align: right;
                .disable {
                    color: #c9c9c9;
                }
            }
        }
    }
}
</style>
