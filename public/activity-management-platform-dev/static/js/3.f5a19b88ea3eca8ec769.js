webpackJsonp([3],{"C57/":function(t,e){},EvGm:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=a("gyMJ"),s=a("0xDb"),r={name:"listStatus",props:{color:{type:String,default:""},desc:{type:String,default:""}}},n={render:function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"list-status"},[e("span",{style:{background:this.color}},[this._v(this._s(this.desc))])])},staticRenderFns:[]};var o=a("VU/8")(r,n,!1,function(t){a("C57/")},"data-v-2d13c738",null).exports,l={components:{ListStatus:o},data:function(){var t=this;this.$createElement;return{form:{type:"",status:"",search:""},typeList:[{value:"",label:"全部类型"},{value:1,label:"开门抽奖"}],statusList:[{value:"",label:"全部状态"},{value:1,label:"审批中"},{value:2,label:"待配置"},{value:3,label:"即将开始"},{value:4,label:"进行中"},{value:5,label:"已下架"},{value:6,label:"已失效"}],list:[],cols:[{label:"活动ID",prop:"number",align:"center"},{label:"活动名称",prop:"name",align:"center"},{label:"活动类型",prop:"type",align:"center",render:function(t,e){return t("div",[t("div",["开门抽奖"])])}},{label:"创建时间",prop:"createAt",align:"center",formatTime:!0},{label:"参与人次",prop:"participants",align:"center"},{label:"活动状态",prop:"status",align:"center",render:function(t,e){return t(o,{props:{desc:["","审批中","待配置","即将开始","进行中","已下架","已失效"][e.row.status],color:["","#cdcdcd","#67c23a","#409EFF","#409EFF","#cdcdcd","#cdcdcd"][e.row.status]}})}}],actions:[{render:function(e,a){var i=e("el-button",{attrs:{type:"text"},on:{click:function(){t.$router.push({name:"activityDetail",query:{id:a.row.id,status:a.row.status,number:a.row.number}})}}},[e("i",{class:"iconfont icon-chakan"})]),s=e("el-button",{attrs:{type:"text"},on:{click:function(){t.$router.push({name:"activityPrizeSetting",query:{id:a.row.id,number:a.row.number}})}}},[e("i",{class:"iconfont icon-bianji"})]),r=e("el-button",{attrs:{type:"text"},on:{click:function(){t.upShelf(a.row.id)}}},[e("i",{class:"iconfont icon-shangjia"})]),n=e("el-button",{attrs:{type:"text"},on:{click:function(){t.lowShelf(a.row.id)}}},[e("i",{class:"iconfont icon-xiajia"})]);return 1==a.row.status||3==a.row.status||6==a.row.status?e("div",[i]):2==a.row.status?e("div",[i,s]):4==a.row.status?e("div",[i,n]):5==a.row.status?e("div",[i,r]):void 0}}],pageState:{pageSize:10,pageNumber:1,total:0}}},mounted:function(){this.fetchData(),this.$nextTick(this.renderBreadCrumbAction)},methods:{renderBreadCrumbAction:function(){var t=this;this.$createElement;this.$bus.$emit("changeBreadCrumbAction",function(e,a){return a("div",{},[a("el-button",{attrs:{size:"medium"},on:{click:t.drawHistory}},["抽奖历史"]),a("el-button",{attrs:{size:"medium",type:"primary"},on:{click:t.newActivity}},["新建活动"])])})},approvalHistory:function(){this.$router.push({name:"activityApprovalList"})},drawHistory:function(){this.$router.push({name:"activityDrawList"})},newActivity:function(){Object(s.m)("activityInfo"),this.$router.push({name:"activityNew"})},upShelf:function(t){var e=this;i.a.post("activityShelf",{id:t}).then(function(t){e.$message({message:"上架成功",type:"success"}),e.fetchData()})},lowShelf:function(t){var e=this;i.a.post("activityObtained",{id:t}).then(function(t){e.$message({message:"下架成功",type:"success"}),e.fetchData()})},fetchData:function(){var t=this,e={type:this.form.type,status:this.form.status?[this.form.status]:[],search:this.form.search,pageNumber:this.pageState.pageNumber,pageSize:this.pageState.pageSize};i.a.post("activityGetlist",e).then(function(e){t.list=e.data.dataList,t.pageState.total=e.data.dataCount})},search:function(){this.pageState.pageSize=10,this.pageState.pageNumber=1,this.fetchData()},reset:function(){this.form.type="",this.form.status="",this.form.search="",this.pageState.pageSize=10,this.pageState.pageNumber=1,this.fetchData()},onPageSize:function(t){this.pageState.pageSize=t,this.fetchData()},onPageNumber:function(t){this.pageState.pageNumber=t,this.fetchData()}}},c={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"activity-list page-list"},[a("el-form",{ref:"form",staticStyle:{"margin-left":"30px"},attrs:{model:t.form,inline:!0}},[a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{staticStyle:{width:"200px"},attrs:{placeholder:"请选择"},model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}},t._l(t.typeList,function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})}))],1),t._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{staticStyle:{width:"200px"},attrs:{placeholder:"请选择"},model:{value:t.form.status,callback:function(e){t.$set(t.form,"status",e)},expression:"form.status"}},t._l(t.statusList,function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})}))],1),t._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-input",{staticStyle:{width:"300px"},attrs:{placeholder:"搜索活动名称或活动id"},model:{value:t.form.search,callback:function(e){t.$set(t.form,"search",e)},expression:"form.search"}})],1),t._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-button",{attrs:{type:"primary"},on:{click:t.search}},[t._v("查询")]),t._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:t.reset}},[t._v("重置查询")])],1)],1),t._v(" "),a("div",{staticClass:"flex1"},[a("Utable",{attrs:{data:t.list,cols:t.cols,actions:t.actions,pageNumber:t.pageState.pageNumber,pageSize:t.pageState.pageSize,total:t.pageState.total},on:{pageSize:t.onPageSize,pageNumber:t.onPageNumber}})],1)],1)},staticRenderFns:[]};var u=a("VU/8")(l,c,!1,function(t){a("n8af")},"data-v-1f1c7d49",null);e.default=u.exports},n8af:function(t,e){}});
//# sourceMappingURL=3.f5a19b88ea3eca8ec769.js.map