webpackJsonp([11],{myIg:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=a("gyMJ"),l={data:function(){return{form:{},checkList:[],levelList:[],prizeList:[]}},mounted:function(){this.getPrizeList(),this.getDetail(),this.$nextTick(this.renderBreadCrumbAction)},methods:{renderBreadCrumbAction:function(){var e=this;this.$createElement;this.$bus.$emit("changeBreadCrumbAction",function(t,a){return 1==e.$route.query.status||6==e.$route.query.status?a("div",{},[a("el-button",{attrs:{size:"medium",type:"primary"},on:{click:e.goBack}},["取消"])]):a("div",{},[a("el-button",{attrs:{size:"medium"},on:{click:e.edit}},["编辑"]),a("el-button",{attrs:{size:"medium",type:"primary"},on:{click:e.goBack}},["取消"])])})},edit:function(){this.$router.push({name:"activityPrizeSetting",query:{id:this.$route.query.id,number:this.$route.query.number}})},goBack:function(){this.$router.back()},getDetail:function(){var e=this;r.a.post("activityGet",{id:this.$route.query.id}).then(function(t){e.form=t.data,t.data.award.length>0&&(e.form.award.forEach(function(t){var a={name:t.name,level:t.level+""};e.levelList.push(a)}),e.checkList=e.form.level.split(","))})},getPrizeList:function(){var e=this,t={search:this.$route.query.number,pageSize:1e3,pageNumber:1};r.a.post("activityAwardGetlist",t).then(function(t){t.data.dataList.length>0&&(e.prizeList=t.data.dataList[0].award)})}}},n={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticStyle:{padding:"10px 30px"}},[a("el-form",{ref:"form",attrs:{model:e.form,"label-width":"100px"}},[a("el-row",[a("el-col",{attrs:{span:12}},[a("el-form-item",{attrs:{label:"活动名称："}},[a("span",[e._v(e._s(e.form.name))])]),e._v(" "),a("el-form-item",{attrs:{label:"活动时间："}},[a("span",[e._v(e._s(e._f("datetime")(e.form.startAt))+"~"+e._s(e._f("datetime")(e.form.endAt)))])]),e._v(" "),a("el-form-item",{attrs:{label:"抽奖次数："}},[a("span",[e._v("每用户共可参加"+e._s(e.form.numberTotal?e.form.numberTotal:"无限")+"次  每用户每天可参加"+e._s(e.form.numberDaily?e.form.numberDaily:"无限")+"次")])])],1),e._v(" "),a("el-col",{attrs:{span:12}},[a("el-form-item",{attrs:{label:"活动类型："}},[a("span",[e._v("开门抽奖")])]),e._v(" "),a("el-form-item",{attrs:{label:"新人专享："}},[a("el-switch",{attrs:{disabled:""},model:{value:!!e.form.new,callback:function(t){e.$set(!!e.form,"new",t)},expression:"!!form.new"}}),e._v("\n                    （新人：注册"+e._s(e.form.newDaylimit)+"天内的用户）\n                ")],1)],1)],1)],1),e._v(" "),e.form.award.length>0?a("div",{staticStyle:{margin:"20px"}},[e._v("奖品设置：")]):e._e(),e._v(" "),e.form.award.length>0?a("div",{staticStyle:{"margin-left":"20px"}},[a("div",[a("el-checkbox-group",{model:{value:e.checkList,callback:function(t){e.checkList=t},expression:"checkList"}},[e._l(e.levelList,function(t){return a("el-checkbox",{key:t.level,attrs:{label:t.level,disabled:""}},[e._v(e._s(t.name))])}),e._v("--\x3e\n                "),a("label",{staticClass:"el-checkbox"},[e._v("的奖品全部发放完时，活动将自动下线")])],2)],1),e._v(" "),a("div",{staticStyle:{"margin-top":"15px"}},[a("el-table",{attrs:{data:e.form.award,border:""}},[a("el-table-column",{attrs:{label:"奖品级别",prop:"name",width:"180",align:"center"}}),e._v(" "),a("el-table-column",{attrs:{label:"奖品类型",width:"180",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("span",[e._v(e._s(t.row.category))])]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"奖品",width:"180",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[e._l(e.prizeList,function(r){return[t.row.awardId==r.id?a("span",{key:r.id},[e._v(e._s(r.name))]):e._e()]})]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"发放数量",width:"180",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("span",[e._v(e._s(t.row.number))])]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"每日上限（数量）",width:"180",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("p",[e._v("全部："+e._s(t.row.allDailyNumber?t.row.allDailyNumber:"不限"))]),e._v(" "),a("p",[e._v("每人："+e._s(t.row.personalDailyNumber?t.row.personalDailyNumber:"不限"))])]}}])}),e._v(" "),e._l(e.form.time,function(t,r){return[a("el-table-column",{key:r,attrs:{label:t,align:"center",width:"190"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("span",[e._v(e._s(t.row.rate[r]))])]}}])})]})],2)],1)]):e._e(),e._v(" "),e.form.changeLog.length>0?a("div",{staticStyle:{margin:"20px"}},[e._v("操作日志：")]):e._e(),e._v(" "),e.form.changeLog.length>0?a("div",{staticStyle:{"margin-top":"15px"}},[a("el-table",{attrs:{data:e.form.changeLog}},[a("el-table-column",{attrs:{label:"操作时间",align:"center"},scopedSlots:e._u([{key:"default",fn:function(t){return[a("span",[e._v(e._s(e._f("datetime")(t.row.create_at)))])]}}])}),e._v(" "),a("el-table-column",{attrs:{label:"操作人",prop:"operator",align:"center"}}),e._v(" "),a("el-table-column",{attrs:{label:"奖品名称",prop:"name",align:"center"}}),e._v(" "),a("el-table-column",{attrs:{label:"变更字段",prop:"field",align:"center"}}),e._v(" "),a("el-table-column",{attrs:{label:"变更前",prop:"before",align:"center"}}),e._v(" "),a("el-table-column",{attrs:{label:"变更后",prop:"after",align:"center"}})],1)],1):e._e()],1)},staticRenderFns:[]},i=a("VU/8")(l,n,!1,null,null,null);t.default=i.exports}});
//# sourceMappingURL=11.7571f0be2380dd0227ab.js.map