webpackJsonp([7],{frnR:function(t,e){},"lPV/":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var l=a("Dd8w"),i=a.n(l),r=a("gyMJ"),n={data:function(){this.$createElement;return{centerDialogVisible:!1,minute:0,downloadUrl:"",form:{type:"",status:"",search:"",startAt:"",endAt:""},typeList:[{label:"全部类型",value:""},{label:"开门抽奖",value:1}],statusList:[{label:"全部领取状态",value:""},{label:"未领取",value:1},{label:"已失效",value:2},{label:"已领取",value:3}],list:[],cols:[{label:"ID",prop:"id",align:"center"},{label:"抽奖时间",prop:"create_at",align:"center",formatTime:!0},{label:"活动ID",prop:"activity_number",align:"center"},{label:"来源活动",prop:"activity_name",align:"center"},{label:"活动类型",prop:"type",align:"center",render:function(t,e){return t("div",[t("div",["开门抽奖"])])}},{label:"彩之云id",prop:"user_id",align:"center"},{label:"手机号码",prop:"user_mobile",align:"center"},{label:"奖品名称",prop:"award_name",align:"center"},{label:"领取状态",prop:"status",align:"center",render:function(t,e){return t("div",[t("div",[["","未领取","已失效","已领取"][e.row.status]])])}}],actions:[],pageState:{pageSize:10,pageNumber:1,total:0}}},mounted:function(){this.fetchData()},methods:{fetchData:function(){var t=this,e=i()({},this.form,{pageSize:this.pageState.pageSize,pageNumber:this.pageState.pageNumber});r.a.post("lotteryParticipateRecordGetList",e).then(function(e){t.list=e.data.dataList,t.pageState.total=e.data.dataCount})},onPageSize:function(t){this.pageState.pageSize=t,this.fetchData()},onPageNumber:function(t){this.pageState.pageNumber=t,this.fetchData()},search:function(){this.pageState.pageSize=10,this.pageState.pageNumber=1,this.fetchData()},exportList:function(){var t=this;r.a.post("lotteryRecordDataExcel",{}).then(function(e){t.minute=e.data.time,t.downloadUrl=e.data.path,t.centerDialogVisible=!0})}}},o={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"activity-list page-list"},[a("el-form",{ref:"form",staticStyle:{"margin-left":"30px"},attrs:{model:t.form,inline:!0}},[a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:t.form.type,callback:function(e){t.$set(t.form,"type",e)},expression:"form.type"}},t._l(t.typeList,function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})}))],1),t._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:t.form.status,callback:function(e){t.$set(t.form,"status",e)},expression:"form.status"}},t._l(t.statusList,function(t){return a("el-option",{key:t.value,attrs:{label:t.label,value:t.value}})}))],1),t._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("FormDatetimeRange",{attrs:{"start-at":t.form.startAt,"end-at":t.form.endAt},on:{"update:startAt":function(e){t.$set(t.form,"startAt",e)},"update:endAt":function(e){t.$set(t.form,"endAt",e)}}})],1),t._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-input",{attrs:{placeholder:"搜索活动名称或活动id"},model:{value:t.form.search,callback:function(e){t.$set(t.form,"search",e)},expression:"form.search"}})],1),t._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-button",{attrs:{type:"primary"},on:{click:t.search}},[t._v("搜索")]),t._v(" "),a("el-button",{on:{click:t.exportList}},[t._v("导出")])],1)],1),t._v(" "),a("div",{staticClass:"flex1"},[a("Utable",{attrs:{data:t.list,cols:t.cols,actions:t.actions,pageNumber:t.pageState.pageNumber,pageSize:t.pageState.pageSize,total:t.pageState.total},on:{pageSize:t.onPageSize,pageNumber:t.onPageNumber}})],1),t._v(" "),a("copy-dialog",{attrs:{visible:t.centerDialogVisible,time:t.minute,url:t.downloadUrl},on:{"update:visible":function(e){t.centerDialogVisible=e}}})],1)},staticRenderFns:[]};var s=a("VU/8")(n,o,!1,function(t){a("frnR")},"data-v-38db8c22",null);e.default=s.exports}});
//# sourceMappingURL=7.d70be1ac0580ee556c08.js.map