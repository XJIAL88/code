<template>
    <div>
        <el-row type="flex">
            <el-col :span="20">
                <div style="padding:10px 15px;">
                    <el-button @click="handleGroupingUser">分组</el-button>
                    <el-button v-if="search.id!=1" @click="handleDeleteUserGroup">删除</el-button>
                    <el-input
                        placeholder="搜索用户账号或昵称"
                        suffix-icon="el-icon-search"
                        @keyup.enter.native="doSearch"
                        v-model="keyword"
                        style="width:300px;margin-left:15px"
                    ></el-input>
                </div>
                <div class="flex1">
                    <Utable
                        :data="list"
                        :cols="cols"
                        :actions="actions"
                        :pagination="false"
                        :selection="true"
                        @selection="selection"
                    />
                </div>
            </el-col>
            <el-col :span="4" class="user-sidebar">
                <template v-for="group in userGroupList">
                    <el-row
                        @click.native="handleToggleGroup(1)"
                        :key="group.id"
                        v-if="group.id==1"
                        class="all"
                        :class="{active: search.id === 1}"
                        type="flex"
                        align="middle"
                    >全部用户（{{group.userCount}}）</el-row>
                </template>
                <el-row class="other">
                    <template v-for="group in userGroupList">
                        <el-row
                            @click.native="handleToggleGroup(group.id)"
                            :class="{active: search.id === group.id}"
                            class="group"
                            :key="group.id"
                            type="flex"
                            align="middle"
                            justify="center"
                            v-if="group.id!=1"
                        >{{group.title}}（{{group.userCount}}）</el-row>
                    </template>
                </el-row>
                <el-row class="add-button" type="flex" align="middle" justify="center">
                    <el-button type="text" @click="handleEditGroup">＋新增分组</el-button>
                    <el-dialog
                        class="dialog-group"
                        title="新增分组"
                        :visible.sync="dialogVisibleGroup"
                        width="20%"
                    >
                        <el-row type="flex" align="middle">
                            <el-input v-model="addGroupName" placeholder="请输入分组名称"></el-input>
                        </el-row>
                        <span slot="footer" class="dialog-footer">
                            <el-button @click="handleCancelGroup">取 消</el-button>
                            <el-button type="primary" @click="handleAddGroup">确 定</el-button>
                        </span>
                    </el-dialog>
                </el-row>
            </el-col>
        </el-row>

        <add-user :visible.sync="showAddUser" :groupId="search.id"></add-user>

        <el-dialog
            class="dialog-group"
            title="请选择分组"
            :visible.sync="dialogVisibleSelect"
            width="30%"
            :before-colse="handleSelectClose"
        >
            <el-select class="dialog-select" v-model="selectGroup" placeholder="请选择">
                <el-option
                    v-for="item in userGroupList"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id"
                ></el-option>
            </el-select>

            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisibleSelect = false">取 消</el-button>
                <el-button type="primary" @click="handleGroupUser">确 定</el-button>
            </span>
        </el-dialog>

        <copy-dialog :visible.sync="centerDialogVisible" :time="minute" :url="downloadUrl"></copy-dialog>
    </div>
</template>

<script>
import api from "@/api";
import addUser from "@/views/user/components/add-user";

export default {
    components: { addUser },
    data() {
        return {
            list: [],
            cols: [
                {
                    label: "ID",
                    prop: "id",
                    align: "center"
                },
                {
                    label: "用户头像",
                    prop: "image",
                    align: "center",
                    render: (h, scope) => {
                        return h("div", [<div />]);
                    }
                },
                {
                    label: "用户昵称",
                    prop: "nickname",
                    align: "center"
                },
                {
                    label: "用户账号",
                    prop: "mobile",
                    align: "center"
                },
                {
                    label: "添加时间",
                    prop: "createAt",
                    align: "center",
                    formatTime: true
                },
                {
                    label: "用户分组",
                    prop: "groups",
                    align: "center"
                }
            ],
            actions: [],
            showAddUser: false,
            dialogVisibleGroup: false,
            dialogVisibleSelect: false,
            selectGroup: "",
            search: {
                id: 1,
                keyword: ""
                // pageNumber: 1,
                // pageSize: 10,
                // total: 0
            },
            keyword: "",
            totalCount: 0,
            userGroupList: [],
            addGroupName: "",
            selectedList: [],
            minute: "1",
            downloadUrl: "123",
            centerDialogVisible: false
        };
    },
    mounted() {
        this.fetchData();
        this.getGroupList();
        this.$nextTick(this.renderBreadCrumbAction);
    },
    methods: {
        renderBreadCrumbAction() {
            this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
                return h("div", {}, [
                    <el-button
                        size="medium"
                        type="primary"
                        onClick={this.exportExcel}
                    >
                        导出excel
                    </el-button>,
                    <el-button
                        size="medium"
                        type="primary"
                        onClick={this.addUser}
                    >
                        添加用户
                    </el-button>
                ]);
            });
        },
        exportExcel() {
            api.post("activityUserDataExcel", {
                activityUserGroupId: this.search.id
            }).then(res => {
                this.minute = res.data.time;
                this.downloadUrl = res.data.path;
                this.centerDialogVisible = true;
            });
            // this.centerDialogVisible = true;
        },
        addUser() {
            this.showAddUser = true;
        },
        /**
         * 获取用户列表
         */
        fetchData() {
            api.post("activityGroupGetWithUsers", this.search).then(res => {
                this.list = res.data.users;
                // this.search.total = res.data.dataCount;
                // if (!this.search.id) {
                //     this.totalCount = res.data.dataCount;
                // }
            });
        },
        doSearch() {
            this.search.keyword = this.keyword;
            this.search.pageNumber = 1;
            this.search.pageSize = 10;
            this.fetchData();
        },
        onPageSize(val) {
            this.search.pageSize = val;
            this.fetchData();
        },
        onPageNumber(val) {
            this.search.pageNumber = val;
            this.fetchData();
        },
        selection(val) {
            this.selectedList = val;
        },
        /**
         * 点击新增分组
         */
        handleEditGroup() {
            this.dialogVisibleGroup = true;
        },
        /**
         * 取消新增分组
         */
        handleCancelGroup() {
            this.dialogVisibleGroup = false;
            this.addGroupName = "";
        },
        /**
         * 新增分组
         */
        handleAddGroup() {
            if (!this.addGroupName) {
                this.$message({
                    messsage: "请输入分组名称",
                    type: "error"
                });
                return;
            }
            api.post("activityGroupInsert", {
                title: this.addGroupName
            }).then(res => {
                this.$message({
                    type: "success",
                    message: "添加成功"
                });
                this.dialogVisibleGroup = false;
                this.addGroupName = "";
                this.getGroupList();
            });
        },
        /**
         * 获取分组列表
         */
        getGroupList() {
            api.post("activityGroupGetList", { title: "" }).then(res => {
                this.userGroupList = res.data.dataList;
            });
        },
        handleToggleGroup(id) {
            this.search.id = id;
            this.fetchData();
        },
        /**
         * 点击分组按钮
         */
        handleGroupingUser() {
            if (!this.selectedList.length) {
                this.$alert("请选择用户", {
                    type: "error"
                });
                return;
            }
            this.dialogVisibleSelect = true;
        },
        /**
         * 分组
         */
        handleGroupUser() {
            let mobileList = [];
            this.selectedList.forEach(item => {
                mobileList.push(item.mobile);
            });
            let params = {
                mobile: mobileList,
                activityUserGroupId: this.selectGroup
            };
            api.post("activityGroupUserJoin", params).then(res => {
                this.dialogVisibleSelect = false;
                this.fetchData();
            });
        },
        handleSelectClose() {
            this.selectGroup = "";
        },
        /**
         * 点击删除按钮
         */
        handleDeleteUserGroup() {
            if (!this.selectedList.length) {
                this.$alert("请选择用户", {
                    type: "error"
                });
                return;
            }
            this.$confirm("此操作将删除该分组关系，是否继续？", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            })
                .then(() => {
                    let delList = [];
                    this.selectedList.forEach(item => {
                        delList.push(item.id);
                    });
                    api.post("activityGroupUserLeave", {
                        id: delList
                    }).then(res => {
                        this.$message({
                            message: "删除成功",
                            type: "success"
                        });
                        this.fetchData();
                    });
                })
                .catch(() => {
                    this.$message({
                        type: "info",
                        message: "已取消删除"
                    });
                });
        }
    }
};
</script>

<style lang="scss" scoped>
.user-sidebar {
    user-select: none;
    max-height: 58rem;
    // overflow-y: scroll;
    font-size: 15px;
    border: 1px solid #f5f5f5;
    .all {
        cursor: pointer;
        height: 4rem;
        padding-left: 20px;
        &.active {
            background-color: rgba(245, 245, 245, 1);
        }
    }
    .other {
        .group {
            cursor: pointer;
            user-select: none;
            padding: 10px 0;
            &.active {
                background-color: rgba(245, 245, 245, 1);
            }
        }
    }
    .add-button {
    }
}
</style>
