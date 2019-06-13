<template>
    <div class="utable">
        <el-table
            stripe
            height="500"
            ref="multipleTable"
            :data="data"
            @row-click="handleRowClick"
            @selection-change="handleSelectionChange"
        >
            <el-table-column type="selection" width="55" v-if="selection"></el-table-column>
            <el-table-column type="index" label="序号" width="50" v-if="index"></el-table-column>
            <template v-for="(col,colIndex) in cols">
                <el-table-column
                    :fixed="col.fixed"
                    :key="colIndex"
                    :prop="col.prop"
                    :label="col.label"
                    :width="col.width"
                    :header-align="col.headerAlign"
                    :align="col.align"
                    :sortable="col.sortable"
                    :show-overflow-tooltip="col.overflow"
                >
                    <template slot-scope="scope">
                        <template v-if="col.render">
                            <Render :renderDom="h=>{ return col.render(h,scope)}"></Render>
                        </template>
                        <template v-else-if="col.formatTime">{{scope.row[col.prop] | datetime}}</template>
                        <template v-else>{{scope.row[col.prop]}}</template>
                    </template>
                </el-table-column>
            </template>
            <el-table-column
                label="操作"
                :fixed="isFixed"
                :width="actions[0].width"
                :header-align="actions[0].headerAlign"
                :align="actions[0].align"
                v-if="actions.length && isFixed"
            >
                <template slot-scope="scope">
                    <template v-for="action in actions">
                        <template v-if="action.render">
                            <Render :renderDom="h=>{ return action.render(h,scope)}"/>
                        </template>
                        <template v-else>
                            <el-button
                                type="text"
                                @click.stop="action.handle(scope)"
                            >{{action.label}}</el-button>
                        </template>
                    </template>
                </template>
            </el-table-column>
            <el-table-column
                label="操作"
                :width="actions[0].width"
                :header-align="actions[0].headerAlign"
                :align="actions[0].align"
                v-else-if="actions.length"
            >
                <template slot-scope="scope">
                    <template v-for="action in actions">
                        <template v-if="action.render">
                            <Render :renderDom="h=>{ return action.render(h,scope)}"/>
                        </template>
                        <template v-else>
                            <el-button
                                type="text"
                                @click.stop="action.handle(scope)"
                            >{{action.label}}</el-button>
                        </template>
                    </template>
                </template>
            </el-table-column>
            <template slot="empty">
                <div class="dytable-view-empty">
                    <img src="./empty.svg">
                    <p>{{emptyText}}</p>
                </div>
            </template>
        </el-table>
        <el-row type="flex" justify="center">
            <el-pagination
                v-if="pagination"
                class="page-list-pagination"
                background
                layout="total,sizes,prev,pager,next,jumper"
                :currentPage.sync="cPageNumber"
                :page-size="pageSize"
                :total="total"
                @size-change="handlePageSizeChange"
                @current-change="handlePageNumberChange"
            ></el-pagination>
        </el-row>
    </div>
</template>

<script>
const Render = {
    props: {
        renderDom: {
            type: Function,
            default: h => h("")
        }
    },
    render(h) {
        return this.renderDom(h);
    }
};

export default {
    name: "uTable",
    components: { Render },
    props: {
        emptyText: {
            type: String,
            default: "暂无数据"
        },
        pagination: {
            type: Boolean,
            default: true
        },
        index: {
            type: Boolean,
            default: false
        },
        selection: {
            type: Boolean,
            default: false
        },
        data: {
            type: Array,
            default: () => []
        },
        total: {
            type: Number,
            default: 0
        },
        pageNumber: {
            type: Number,
            default: 1
        },
        pageSize: {
            type: Number,
            default: 10
        },
        cols: {
            /*
             * cols:[
             *   {
             *       label: String,
             *       prop: String,
             *       width: Number,
             *       headerAlign: String,
             *       align：String,
             *       overflow: Boolean,
             *       sortable: Boolean,
             *       formatTime: Boolean,
             *       render: Function,
             *   }
             * ]
             * */
            type: Array,
            default: () => []
        },
        actions: {
            /*
             * actions:[
             *   {
             *       label: String,
             *       handle: Function,
             *       render: Function,
             *   }
             * ]
             * */
            type: Array,
            default: () => []
        },
        isFixed: {
            type: String,
            default: ""
        }
    },
    watch: {
        pageNumber(val) {
            this.cPageNumber = val;
        }
    },
    data() {
        return {
            cPageNumber: 1
        };
    },
    methods: {
        handlePageSizeChange(val) {
            this.$emit("pageSize", val);
        },
        handlePageNumberChange(val) {
            this.$emit("pageNumber", val);
        },
        handleRowClick(row, event, column) {
            this.$emit("rowClick", row, event, column);
        },
        handleSelectionChange(val) {
            this.$emit("selection", val);
        }
    }
};
</script>

<style scoped lang="scss">
.utable {
    display: flex;
    flex-direction: column;
    // padding-top: 25px;
    // max-height: 550px;
    height: 100%;
    box-sizing: border-box;
    /deep/ .el-table__header-wrapper {
        thead {
            th {
                // background: #eaedf2;
                font-size: 14px;
                color: #000;
            }
        }
    }
}
</style>
