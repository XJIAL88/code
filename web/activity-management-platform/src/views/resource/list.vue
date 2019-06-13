<template>
    <div class="resource-list page-list">
        <el-form :model="form" ref="form">
            <el-row>
                <el-col :span="6">
                    <el-form-item label-width="15px">
                        <el-cascader
                            clearable
                            :options="categoryList"
                            v-model="form.categoryId"
                            @change="changeCategory"
                            :props="props"
                            :show-all-levels="false"
                            placeholder="全部类型"
                        ></el-cascader>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label-width="15px">
                        <el-input v-model="form.search" placeholder="搜索资源名称或资源id"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label-width="15px">
                        <el-button @click="search" type="primary">查询</el-button>
                        <el-button @click="reset" type="primary">重置查询</el-button>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <el-menu
            :default-active="activeIndex"
            class="el-menu-demo"
            mode="horizontal"
            @select="handleSelect"
        >
            <el-menu-item index="1">奖品列表</el-menu-item>
            <el-menu-item index="2">资源列表</el-menu-item>
        </el-menu>
        <div class="flex1">
            <Utable
                :data="list"
                :cols="cols"
                :actions="actions"
                :pageNumber="pageState.pageNumber"
                :pageSize="pageState.pageSize"
                :total="pageState.total"
                @pageSize="onPageSize"
                @pageNumber="onPageNumber"
            />
        </div>
    </div>
</template>

<script>
import api from "@/api";
export default {
    data() {
        return {
            activeIndex: "2",
            form: {
                categoryId: [],
                search: ""
            },
            typeList: [],
            statusList: [],
            list: [],
            cols: [
                {
                    label: "资源ID",
                    prop: "resourceId",
                    align: "center"
                },
                {
                    label: "资源名称",
                    prop: "resourceName",
                    align: "center"
                },
                {
                    label: "活动名称",
                    prop: "activityName",
                    align: "center"
                },
                {
                    label: "有效时间",
                    prop: "effectiveTime",
                    align: "center",
                    formatTime: true
                },
                {
                    label: "总数",
                    prop: "number",
                    align: "center"
                },
                {
                    label: "已冻结",
                    prop: "freeze",
                    align: "center"
                },
                {
                    label: "已使用",
                    prop: "usedNumber",
                    align: "center"
                }
            ],
            actions: [],
            pageState: {
                pageSize: 10,
                pageNumber: 1,
                total: 0
            },
            categoryList: [],
            props: {
                label: "title",
                value: "id",
                children: "son"
            }
        };
    },
    mounted() {
        this.fetchData();
        this.getCategoryList();
        // this.$nextTick(this.renderBreadCrumbAction);
    },
    methods: {
        renderBreadCrumbAction() {
            this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
                return h("div", {}, [
                    <el-button size="medium" onClick={this.approvalHistory}>
                        审批历史
                    </el-button>,
                    <el-button
                        size="medium"
                        type="primary"
                        onClick={this.apply}
                    >
                        申请资源
                    </el-button>
                ]);
            });
        },
        fetchData() {
            api.post("activityResourceDetail", {
                orderNumber: "",
                search: this.form.search,
                categoryId:
                    this.form.categoryId.length > 0
                        ? this.form.categoryId[this.form.categoryId.length - 1]
                        : "",
                pageSize: this.pageState.pageSize,
                pageNumber: this.pageState.pageNumber
            }).then(res => {
                this.list = res.data.dataList;
                this.pageState.total = res.data.dataCount;
            });
        },
        onPageSize(val) {
            this.pageState.pageSize = val;
            this.fetchData();
        },
        onPageNumber(val) {
            this.pageState.pageNumber = val;
            this.fetchData();
        },
        handleSelect(tab) {
            if (tab == 1) {
                this.$router.push({
                    name: "prizeList"
                });
            }
        },
        getCategoryList() {
            api.post("resourceCategoryList").then(res => {
                this.categoryList = res.data.dataList;
            });
        },
        changeCategory(val) {
            console.log(val);
        },
        search() {
            this.pageState.pageSize = 10;
            this.pageState.pageNumber = 1;
            this.fetchData();
        },
        reset() {
            this.form.search = "";
            this.form.categoryId = [];
            this.pageState.pageSize = 10;
            this.pageState.pageNumber = 1;
            this.fetchData();
        }
    }
};
</script>

<style lang="scss" scoped>
.resource-list {
    padding: 15px 30px;
    .el-form-item {
        margin-bottom: 0;
    }
}
</style>

