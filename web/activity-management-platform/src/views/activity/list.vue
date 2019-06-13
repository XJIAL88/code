
<style lang="scss">
.select {
  background: #f8f8f8;
  box-shadow: 0 4px 4px 0 rgba(204, 204, 204, 0.9);
  border-radius: 12px;
}
.select input,
.select option {
  background: #ffffff;
  border: 2px solid #8793f3;
  box-shadow: 0 4px 4px 0 rgba(204, 204, 204, 0.9);
  border-radius: 12px;
  color: #8793f3;
}
.btn {
  background: #8793f3;
  box-shadow: 0 4px 4px 0 rgba(204, 204, 204, 0.9);
  border-radius: 12px;
  &:hover {
    background: #8793f3;
  }
}

.table {
  margin-left: 30px;
}
</style>

<template>
  <div class="activity-list page-list">
    <el-form :model="form" ref="form" :inline="true" style="margin-left:30px">
      <el-form-item label-width="15px">
        <!-- <el-select v-model="form.type" placeholder="请选择" style="width:200px">
                    <el-option
                        v-for="item in typeList"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                    ></el-option>
        </el-select>-->
      </el-form-item>
      <el-form-item label-width="15px">
        <el-select v-model="form.status" placeholder="请选择" style="width:200px" class="select">
          <el-option
            class="selectBtn"
            v-for="item in statusList"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item label-width="15px">
        <el-input
          v-model="form.search"
          placeholder="搜索活动名称或活动id"
          style="width:300px;"
          class="select"
        ></el-input>
      </el-form-item>
      <el-form-item label-width="15px">
        <el-button @click="search" type="primary" class="btn">查询</el-button>
        <el-button @click="reset" type="primary" class="btn">重置查询</el-button>
      </el-form-item>
    </el-form>
    <!-- <div class="flex1">
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
    </div>-->
    <div class="table">
      <el-table :data="tableData4" style="width: 70%" max-height="250">
        <el-table-column fixed prop="date" label="日期" width="200"></el-table-column>
        <el-table-column prop="name" label="姓名" width="200"></el-table-column>
        <el-table-column prop="province" label="省份" width="200"></el-table-column>
        <el-table-column prop="city" label="市区" width="200"></el-table-column>
        <el-table-column prop="address" label="地址" width="200"></el-table-column>
        <el-table-column prop="zip" label="邮编" width="200"></el-table-column>
        <el-table-column fixed="right" label="操作" width="120">
          <template slot-scope="scope">
            <el-button
              @click.native.prevent="deleteRow(scope.$index, tableData4)"
              type="text"
              size="small"
            >移除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import api from "@/api";
import { sessionStorageRemove } from "@/utils";
import ListStatus from "./component/list-status";
export default {
  components: { ListStatus },
  data() {
    return {
      tableData4: [
        {
          date: "2016-05-03",
          name: "王小虎",
          province: "上海",
          city: "普陀区",
          address: "上海市普陀区金沙江路 1518 弄",
          zip: 200333
        }
      ],
      form: {
        type: "",
        status: "",
        search: ""
      },
      typeList: [
        {
          value: "",
          label: "全部类型"
        },
        {
          value: 1,
          label: "开门抽奖"
        }
      ],
      statusList: [
        {
          value: "",
          label: "全部状态"
        },
        {
          value: 1,
          label: "审批中"
        },
        {
          value: 2,
          label: "待配置"
        },
        {
          value: 3,
          label: "即将开始"
        },
        {
          value: 4,
          label: "进行中"
        },
        {
          value: 5,
          label: "已下架"
        },
        {
          value: 6,
          label: "已失效"
        }
      ],
      list: [],
      cols: [
        {
          label: "活动ID",
          prop: "number",
          align: "center"
        },
        {
          label: "活动名称",
          prop: "name",
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
          label: "创建时间",
          prop: "createAt",
          align: "center",
          formatTime: true
        },
        {
          label: "参与人次",
          prop: "participants",
          align: "center"
        },
        {
          label: "活动状态",
          prop: "status",
          align: "center",
          render: (h, scope) => {
            let list = [
              "",
              "审批中",
              "待配置",
              "即将开始",
              "进行中",
              "已下架",
              "已失效"
            ];
            let colorList = [
              "",
              "#cdcdcd",
              "#67c23a",
              "#409EFF",
              "#409EFF",
              "#cdcdcd",
              "#cdcdcd"
            ];
            return h(ListStatus, {
              props: {
                desc: list[scope.row.status],
                color: colorList[scope.row.status]
              }
            });
          }
        }
      ],
      actions: [
        {
          // align: "center",
          render: (h, scope) => {
            let str1 = (
              <el-button
                type="text"
                onClick={() => {
                  this.$router.push({
                    name: "activityDetail",
                    query: {
                      id: scope.row.id,
                      status: scope.row.status,
                      number: scope.row.number
                    }
                  });
                }}
              >
                <i class="iconfont icon-chakan" />
              </el-button>
            );
            let str2 = (
              <el-button
                type="text"
                onClick={() => {
                  this.$router.push({
                    name: "activityPrizeSetting",
                    query: {
                      id: scope.row.id,
                      number: scope.row.number
                    }
                  });
                }}
              >
                <i class="iconfont icon-bianji" />
              </el-button>
            );
            let str3 = (
              <el-button
                type="text"
                onClick={() => {
                  this.upShelf(scope.row.id);
                }}
              >
                <i class="iconfont icon-shangjia" />
              </el-button>
            );
            let str4 = (
              <el-button
                type="text"
                onClick={() => {
                  this.lowShelf(scope.row.id);
                }}
              >
                <i class="iconfont icon-xiajia" />
              </el-button>
            );
            if (
              scope.row.status == 1 ||
              scope.row.status == 3 ||
              scope.row.status == 6
            ) {
              return h("div", [str1]);
            }
            if (scope.row.status == 2) {
              return h("div", [str1, str2]);
            }
            if (scope.row.status == 4) {
              return h("div", [str1, str4]);
            }
            if (scope.row.status == 5) {
              return h("div", [str1, str3]);
            }
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
  mounted() {
    this.fetchData();
    this.$nextTick(this.renderBreadCrumbAction);
  },
  methods: {
    renderBreadCrumbAction() {
      this.$bus.$emit("changeBreadCrumbAction", (c, h) => {
        return h("div", {}, [
          <el-button size="medium" onClick={this.drawHistory}>
            抽奖历史
          </el-button>,
          <el-button size="medium" type="primary" onClick={this.newActivity}>
            新建活动
          </el-button>
        ]);
      });
    },
    /**
     * 审批历史
     */
    approvalHistory() {
      this.$router.push({
        name: "activityApprovalList"
      });
    },
    /**
     * 抽奖历史
     */
    drawHistory() {
      this.$router.push({
        name: "activityDrawList"
      });
    },
    /**
     * 新建活动
     */
    newActivity() {
      sessionStorageRemove("activityInfo");
      this.$router.push({
        name: "activityNew"
      });
    },
    upShelf(id) {
      api.post("activityShelf", { id }).then(res => {
        this.$message({
          message: "上架成功",
          type: "success"
        });
        this.fetchData();
      });
    },
    lowShelf(id) {
      api.post("activityObtained", { id }).then(res => {
        this.$message({
          message: "下架成功",
          type: "success"
        });
        this.fetchData();
      });
    },
    fetchData() {
      let params = {
        type: this.form.type,
        status: this.form.status ? [this.form.status] : [],
        search: this.form.search,
        pageNumber: this.pageState.pageNumber,
        pageSize: this.pageState.pageSize
      };
      api.post("activityGetlist", params).then(res => {
        this.list = res.data.dataList;
        this.pageState.total = res.data.dataCount;
      });
    },
    search() {
      this.pageState.pageSize = 10;
      this.pageState.pageNumber = 1;
      this.fetchData();
    },
    reset() {
      this.form.type = "";
      this.form.status = "";
      this.form.search = "";
      this.pageState.pageSize = 10;
      this.pageState.pageNumber = 1;
      this.fetchData();
    },
    onPageSize(val) {
      this.pageState.pageSize = val;
      this.fetchData();
    },
    onPageNumber(val) {
      this.pageState.pageNumber = val;
      this.fetchData();
    },
    deleteRow(index, rows) {
      rows.splice(index, 1);
    }
  }
};
</script>

<style lang="scss" scoped>
.activity-list {
  padding: 10px 0;
}
</style>

