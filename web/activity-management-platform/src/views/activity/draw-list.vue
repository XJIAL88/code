<template>
    <div class="activity-list page-list">
        <el-form :model="form" ref="form" :inline="true" style="margin-left:30px">
            <el-form-item label-width="15px">
                <el-select v-model="form.type" placeholder="请选择">
                    <el-option
                        v-for="item in typeList"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                    ></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label-width="15px">
                <el-select v-model="form.status" placeholder="请选择">
                    <el-option
                        v-for="item in statusList"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                    ></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label-width="15px">
                <FormDatetimeRange :start-at.sync="form.startAt" :end-at.sync="form.endAt"></FormDatetimeRange>
            </el-form-item>
            <el-form-item label-width="15px">
                <el-input v-model="form.search" placeholder="搜索活动名称或活动id"></el-input>
            </el-form-item>

            <el-form-item label-width="15px">
                <el-button @click="search" type="primary">搜索</el-button>
                <el-button @click="exportList">导出</el-button>
            </el-form-item>
        </el-form>
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

        <copy-dialog :visible.sync="centerDialogVisible" :time="minute" :url="downloadUrl"></copy-dialog>
    </div>
</template>

<script>
import api from "@/api";
export default {
    data() {
        return {
            centerDialogVisible: false,
            minute: 0,
            downloadUrl: "",
            form: {
                type: "",
                status: "",
                search: "",
                startAt: "",
                endAt: ""
            },
            typeList: [
                {
                    label: "全部类型",
                    value: ""
                },
                {
                    label: "开门抽奖",
                    value: 1
                }
            ],
            statusList: [
                {
                    label: "全部领取状态",
                    value: ""
                },
                {
                    label: "未领取",
                    value: 1
                },
                {
                    label: "已失效",
                    value: 2
                },
                {
                    label: "已领取",
                    value: 3
                }
            ],
            list: [],
            cols: [
                {
                    label: "ID",
                    prop: "id",
                    align: "center"
                },
                {
                    label: "抽奖时间",
                    prop: "create_at",
                    align: "center",
                    formatTime: true
                },
                {
                    label: "活动ID",
                    prop: "activity_number",
                    align: "center"
                },
                {
                    label: "来源活动",
                    prop: "activity_name",
                    align: "center"
                },
                {
                    label: "活动类型",
                    prop: "type",
                    align: "center",
                    render: (h, scope) => {
                        return h("div", [<div>开门抽奖</div>]);
                    }
                },
                {
                    label: "彩之云id",
                    prop: "user_id",
                    align: "center"
                },
                {
                    label: "手机号码",
                    prop: "user_mobile",
                    align: "center"
                },
                {
                    label: "奖品名称",
                    prop: "award_name",
                    align: "center"
                },
                {
                    label: "领取状态",
                    prop: "status",
                    align: "center",
                    render: (h, scope) => {
                        let arr = ["", "未领取", "已失效", "已领取"];
                        return h("div", [<div>{arr[scope.row.status]}</div>]);
                    }
                }
            ],
            actions: [],
            pageState: {
                pageSize: 10,
                pageNumber: 1,
                total: 0
            }
        };
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            let params = {
                ...this.form,
                pageSize: this.pageState.pageSize,
                pageNumber: this.pageState.pageNumber
            };
            api.post("lotteryParticipateRecordGetList", params).then(res => {
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
        search() {
            this.pageState.pageSize = 10;
            this.pageState.pageNumber = 1;
            this.fetchData();
        },
        exportList() {
            let params = {};
            api.post("lotteryRecordDataExcel", params).then(res => {
                this.minute = res.data.time;
                this.downloadUrl = res.data.path;
                this.centerDialogVisible = true;
            });
        }
    }
};
</script>

<style lang="scss" scoped>
.activity-list {
    padding: 10px 0;
}
</style>

