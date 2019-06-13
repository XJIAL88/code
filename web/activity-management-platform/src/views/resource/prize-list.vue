<template>
    <div class="prize-list">
        <el-input
            v-model="keyword"
            @keyup.enter.native="doSearch"
            style="width:400px"
            placeholder="搜索资源/活动名称或id"
            suffix-icon="el-icon-search"
        ></el-input>
        <el-menu
            :default-active="activeIndex"
            class="el-menu-demo"
            mode="horizontal"
            @select="handleSelect"
        >
            <el-menu-item index="1">奖品列表</el-menu-item>
            <el-menu-item index="2">资源列表</el-menu-item>
        </el-menu>
        <div class="prize-table">
            <el-row class="table-header">
                <el-col :span="3">资源类型</el-col>
                <el-col :span="3">奖品名称</el-col>
                <el-col :span="3">奖品图片</el-col>
                <el-col :span="3">剩余</el-col>
                <el-col :span="3">已发放</el-col>
                <el-col :span="5">创建时间</el-col>
                <el-col :span="4">操作</el-col>
                <el-col :span="3"></el-col>
            </el-row>
            <el-row
                v-for="activity in list"
                :key="activity.id"
                style=" border-bottom: 1px solid #ebeef5;"
            >
                <div style="color:#aaa;padding-top:12px;">
                    <span>活动名称：{{activity.name}}</span>
                    <span
                        style="margin-left:15px;"
                    >活动时间：{{activity.startAt|datetime}}~{{activity.endAt|datetime}}</span>
                    <span style="margin-left:15px;">活动id：{{activity.number}}</span>
                </div>
                <el-row class="table-content" v-for="item in activity.award" :key="item.id">
                    <!-- 资源类型 -->
                    <el-col :span="3">{{item.categoryName}}&nbsp;</el-col>
                    <!-- 奖品名称 -->
                    <el-col :span="3">{{item.name}}</el-col>
                    <!-- 奖品图片 -->
                    <el-col :span="3">
                        <img :src="upload.domain+item.image" style="width:40px;height:40px;">
                    </el-col>
                    <!-- 剩余 -->
                    <el-col :span="3">{{item.awardNumber-item.usedNumber}}</el-col>
                    <!-- 已发放 -->
                    <el-col :span="3">{{item.usedNumber}}</el-col>
                    <!-- 创建时间 -->
                    <el-col :span="5">{{item.createAt|datetime}}</el-col>
                    <!-- 操作 -->
                    <el-col :span="4">
                        <span
                            class="operation-btn"
                            style="margin-left:15px"
                            @click="editPrize(item.id)"
                        >
                            <i class="iconfont icon-bianji"/>
                        </span>
                    </el-col>
                </el-row>
            </el-row>
        </div>
        <el-row type="flex" justify="center">
            <el-pagination
                class="page-list-pagination"
                background
                layout="total,sizes,prev,pager,next,jumper"
                :currentPage.sync="params.pageNumber"
                :page-size="params.pageSize"
                :total="total"
                @size-change="handlePageSizeChange"
                @current-change="handlePageNumberChange"
            ></el-pagination>
        </el-row>
    </div>
</template>

<script>
import api from "@/api";
import { UPLOAD_DOMAIN } from "@/api/config.js";
export default {
    data() {
        return {
            activeIndex: "1",
            keyword: "",
            params: {
                search: "",
                pageSize: 10,
                pageNumber: 1
            },
            list: [],
            upload: {
                domain: UPLOAD_DOMAIN
            },
            total: 0
        };
    },
    mounted() {
        this.fetchData();
        this.$nextTick(this.renderBreadCrumbAction);
    },
    methods: {
        renderBreadCrumbAction() {
            this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
                return h("div", {}, [
                    <el-button
                        size="medium"
                        type="primary"
                        onClick={this.newPrize}
                    >
                        新增奖品
                    </el-button>
                ]);
            });
        },
        newPrize() {
            this.$router.push({
                name: "prizeNew"
            });
        },
        fetchData() {
            api.post("activityAwardGetlist", this.params).then(res => {
                this.list = res.data.dataList;
                this.total = res.data.dataCount;
            });
        },
        editPrize(id) {
            this.$router.push({
                name: "prizeEdit",
                query: {
                    id
                }
            });
        },
        doSearch() {
            this.params.search = this.keyword;
            this.params.pageSize = 10;
            this.params.pageNumber = 1;
            this.fetchData();
        },
        handleSelect(tab) {
            if (tab == 2) {
                this.$router.push({
                    name: "resourceList"
                });
            }
        },
        handlePageSizeChange(val) {
            this.params.pageSize = val;
            this.fetchData();
        },
        handlePageNumberChange(val) {
            this.params.pageNumber = val;
            this.fetchData();
        }
    }
};
</script>

<style lang="scss" scoped>
.prize-list {
    padding: 15px 30px;
    .table-header {
        padding: 12px 0;
        border-bottom: 1px solid #ebeef5;
        font-size: 14px;
        color: #000;
        font-weight: bold;
    }
    .table-content {
        padding: 12px 0;
        font-size: 14px;
        color: #000;
        vertical-align: middle;
    }
    .prize-table {
        padding: 10px 0;
    }
    .operation-btn {
        color: #409eff;
        cursor: pointer;
    }
}
</style>
