webpackJsonp([6],{"lPV/":function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var l=a("Dd8w"),i=a.n(l),r=a("gyMJ"),n={data:function(){this.$createElement;return{centerDialogVisible:!1,minute:0,downloadUrl:"",form:{type:"",status:"",search:"",startAt:"",endAt:""},typeList:[{label:"全部类型",value:""},{label:"开门抽奖",value:1}],statusList:[{label:"全部领取状态",value:""},{label:"未领取",value:1},{label:"已失效",value:2},{label:"已领取",value:3}],list:[],cols:[{label:"ID",prop:"id",align:"center"},{label:"抽奖时间",prop:"create_at",align:"center",formatTime:!0},{label:"活动ID",prop:"activity_number",align:"center"},{label:"来源活动",prop:"activity_name",align:"center"},{label:"活动类型",prop:"type",align:"center",render:function(e,t){return e("div",[e("div",["开门抽奖"])])}},{label:"彩之云id",prop:"user_id",align:"center"},{label:"手机号码",prop:"user_mobile",align:"center"},{label:"奖品名称",prop:"award_name",align:"center"},{label:"领取状态",prop:"status",align:"center",render:function(e,t){return e("div",[e("div",[["","未领取","已失效","已领取"][t.row.status]])])}}],actions:[],pageState:{pageSize:10,pageNumber:1,total:0}}},mounted:function(){this.fetchData()},methods:{fetchData:function(){var e=this,t=i()({},this.form,{pageSize:this.pageState.pageSize,pageNumber:this.pageState.pageNumber});r.a.post("lotteryParticipateRecordGetList",t).then(function(t){e.list=t.data.dataList,e.pageState.total=t.data.dataCount})},onPageSize:function(e){this.pageState.pageSize=e},onPageNumber:function(e){this.pageState.pageNumber=e},search:function(){this.pageState.pageSize=10,this.pageState.pageNumber=1,this.fetchData()},exportList:function(){var e=this;r.a.post("lotteryRecordDataExcel",{}).then(function(t){e.minute=t.data.time,e.downloadUrl=t.data.path,e.centerDialogVisible=!0})}}},o={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"activity-list page-list"},[a("el-form",{ref:"form",staticStyle:{"margin-left":"30px"},attrs:{model:e.form,inline:!0}},[a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}},e._l(e.typeList,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-select",{attrs:{placeholder:"请选择"},model:{value:e.form.status,callback:function(t){e.$set(e.form,"status",t)},expression:"form.status"}},e._l(e.statusList,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("FormDatetimeRange",{attrs:{"start-at":e.form.startAt,"end-at":e.form.endAt},on:{"update:startAt":function(t){e.$set(e.form,"startAt",t)},"update:endAt":function(t){e.$set(e.form,"endAt",t)}}})],1),e._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-input",{attrs:{placeholder:"搜索活动名称或活动id"},model:{value:e.form.search,callback:function(t){e.$set(e.form,"search",t)},expression:"form.search"}})],1),e._v(" "),a("el-form-item",{attrs:{"label-width":"15px"}},[a("el-button",{attrs:{type:"primary"},on:{click:e.search}},[e._v("搜索")]),e._v(" "),a("el-button",{on:{click:e.exportList}},[e._v("导出")])],1)],1),e._v(" "),a("div",{staticClass:"flex1"},[a("Utable",{attrs:{data:e.list,cols:e.cols,actions:e.actions,pageNumber:e.pageState.pageNumber,pageSize:e.pageState.pageSize,total:e.pageState.total},on:{pageSize:e.onPageSize,pageNumber:e.onPageNumber}})],1),e._v(" "),a("copy-dialog",{attrs:{visible:e.centerDialogVisible,time:e.minute,url:e.downloadUrl},on:{"update:visible":function(t){e.centerDialogVisible=t}}})],1)},staticRenderFns:[]};var s=a("VU/8")(n,o,!1,function(e){a("peL9")},"data-v-91e444c2",null);t.default=s.exports},peL9:function(e,t){}});
//# sourceMappingURL=6.8fb8e5120f19a52d42ce.js.map