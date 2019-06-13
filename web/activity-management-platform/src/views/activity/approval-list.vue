<template>
    <div class="activity-list page-list">
        <el-form :model="form" ref="form">
            <el-row>
                <el-col :span="6">
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
                </el-col>
                <el-col :span="6">
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
                </el-col>
                <el-col :span="6">
                    <el-form-item label-width="15px">
                        <el-input v-model="form.desc" placeholder="搜索活动名称或活动id"></el-input>
                    </el-form-item>
                </el-col>
                <!-- <el-col :span="6">
                    <el-form-item label-width="15px">
                        <el-button @click="search" type="primary">查询</el-button>
                        <el-button @click="reset" type="primary">重置查询</el-button>
                    </el-form-item>
                </el-col>-->
            </el-row>
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                type: "",
                status: "",
                desc: ""
            },
            typeList: [],
            statusList: [],
            list: [],
            cols: [
                {
                    label: "活动ID",
                    prop: "id",
                    align: "center"
                },
                {
                    label: "活动名称",
                    prop: "id",
                    align: "center"
                },
                {
                    label: "活动类型",
                    prop: "id",
                    align: "center"
                },
                {
                    label: "创建时间",
                    prop: "id",
                    align: "center"
                },
                {
                    label: "审批状态",
                    prop: "id",
                    align: "center"
                }
            ],
            actions: [
                {
                    // align: "center",
                    render: (h, scope) => {
                        return h("div", [
                            <el-button
                                type="text"
                                onClick={() => {
                                    // this.$router.push({
                                    //     name: "resourceApplyDetail",
                                    //     query: {
                                    //         id: scope.row.id,
                                    //         status: scope.row.approvalStatus
                                    //     }
                                    // });
                                }}
                            >
                                查看
                            </el-button>
                        ]);
                    }
                }
            ],
            pageState: {
                pageSize: 10,
                pageNumber: 1,
                total: 0
            }
        };
    },
    mounted() {},
    methods: {
        onPageSize(val) {
            this.pageState.pageSize = val;
        },
        onPageNumber(val) {
            this.pageState.pageNumber = val;
        }
    }
};
</script>

<style lang="scss" scoped>
.activity-list {
    padding: 10px 0;
}
</style>

