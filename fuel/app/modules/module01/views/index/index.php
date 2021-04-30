<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FuelPHP Framework</title>
	<?php echo Asset::css('bootstrap.css'); ?>


<link href='../lib/fullcalendar.min.css' rel='stylesheet' />
<link href='../lib/fullcalendar.print.min.css' rel='stylesheet' media='print' />

<script src='../lib/moment.min.js'></script>
<script src='../lib/jquery.min.js'></script>
<script src='../lib/fullcalendar.min.js'></script>



	<style>
		a{
			color: #883ced;
		}
		a:hover{
			color: #af4cf0;
		}
		.btn.btn-primary{color:#ffffff!important;background-color:#883ced;background-repeat:repeat-x;background-image:-khtml-gradient(linear, left top, left bottom, from(#fd6ef7), to(#883ced));background-image:-moz-linear-gradient(top, #fd6ef7, #883ced);background-image:-ms-linear-gradient(top, #fd6ef7, #883ced);background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0%, #fd6ef7), color-stop(100%, #883ced));background-image:-webkit-linear-gradient(top, #fd6ef7, #883ced);background-image:-o-linear-gradient(top, #fd6ef7, #883ced);background-image:linear-gradient(top, #fd6ef7, #883ced);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fd6ef7', endColorstr='#883ced', GradientType=0);text-shadow:0 -1px 0 rgba(0, 0, 0, 0.25);border-color:#883ced #883ced #003f81;border-color:rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);}
		/* body { margin: 0px 0px 40px 0px; } */

body {
  margin: 40px 10px;
  padding: 0;
  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
  font-size: 14px;
}

#calendar {
  max-width: 450px;
  margin: 0 auto;
}

/* 日曜日 */
.fc-sun {
  color: red;
  background-color: #fff0f0;
}
  
/* 土曜日 */
.fc-sat {
  color: blue;
  background-color: #f0f0ff;
}

	</style>
</head>

  <div id='calendar'></div>
  <br>
  <div style="text-align:center;">
    <input type="checkbox" id="set_sunday" class="my_check_box">
    <label for="set_sunday">日</label>
    <input type="checkbox" id="set_monday" class="my_check_box">
    <label for="set_monday">月</label>
    <br>
    <button id="all_delete">全削除</button>
    <button id="all_get">全取得</button>
    <br>
    <button id="save">保存</button>
  </div>


<script>

$(document).ready(function() {

    $('#calendar').fullCalendar({

    views: {
        month: {
        titleFormat: "YYYY年MMMM",
        },
        week: {
        columnFormat: "dddd d",
        },
        day: {
        titleFormat: "dddd d MMMM YYYY",
        columnFormat: "dddd d",
        }
    },


    // ボタン文字列
    buttonText: {
        prev:   '<', // <
        next:   '>', // >
        prevYear: '<<',  // <<
        nextYear: '>>',  // >>
        today:  '今日',
        month:  '月',
        week:   '週',
        day:    '日'
    },
    // 月名称
    monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    // 月略称
    monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
    // 曜日名称
    dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
    // 曜日略称
    dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
    // 選択可
    selectable: true,
    // 選択時にプレースホルダーを描画
    selectHelper: true,
    // 自動選択解除
    unselectAuto: true,
    // 自動選択解除対象外の要素
    unselectCancel: '',

    // イベントソース
    eventSources: [
        {
        events: [
            {
            id: 1,
            title: '休1',
            start: '2021-04-01'
            },
            {
            title: '休2',
            start: '2021-04-14',
            end: '2021-04-15'
            },
            {
            title: '休3',
            start: '2021-04-07 12:30',
            allDay: false
            }
        ]
        }
    ],

    // 表示されたとき
    viewDisplay: function(view){
    //処理
    },

    // カレンダー空白部分クリック時
    dayClick: function(date, allDay, jsEvent, view) {

        //var title = prompt('予定を入力してください:');
        var title = '休';
        $('#calendar').fullCalendar('addEventSource', [
        {
            id:date,
            title: title,
            start: date,
            allDay: allDay
        }
        ]);
    
    },

    // 設定済みイベントクリック時
    eventClick: function(event) {
        console.log(event);
        console.log(event._id);
        $('#calendar').fullCalendar("removeEvents", event._id);

    }
    
    });

    // 全削除
    $('#all_delete').click(function() {
      $('#calendar').fullCalendar("removeEvents");
    });

    // 全てのイベントID取得
    $('#all_get').click(function() {
      // https://www.javaer101.com/ja/article/42272513.html
      


      var all_event = $('#calendar').fullCalendar('clientEvents');

      // console.log(moment(all_event[0].start).format("YYYY-MM-DD"));

      all_event.sort(function(a,b) {
          // return a.start._i - b.start._i;
          return a.start._d - b.start._d;
      });
      var all_id = all_event.map(x => x._id);
      console.log(all_id);

      var all_date = all_event.map(x => moment(x.start).format("YYYY-MM-DD"))
      console.log(all_date);

    });

    // 保存
    $('#save').click(function(){

      var all_event = $('#calendar').fullCalendar('clientEvents');

      all_event.sort(function(a,b) {
          return a.start._d - b.start._d;
      });

      var all_date = all_event.map(x => moment(x.start).format("YYYY-MM-DD"))
      console.log(all_date);
      param = {
        'start': all_date
      }

      $.ajax({
          type: 'POST',
          url: '<?php echo Uri::create('/module01/api/holiday/multiregister');?>',
          data: param,
          dataType: "json",
      }).done(function(res) {
        alert('done');
        console.log(res);
      }).fail(function(ex) {
        // window.alert('正しい結果を得られませんでした。');
        console.log(ex)
      });

    });


    $("#set_sunday").on("click", function (evt) {
        chk_status = $(this).prop('checked');
        
        if (!chk_status) {
        $('#calendar').fullCalendar("removeEvents", 2);
        $('#calendar').fullCalendar("removeEvents", 3);
        $('#calendar').fullCalendar("removeEvents", 4);
        $('#calendar').fullCalendar("removeEvents", 5);
        } else {
        $('#calendar').fullCalendar('addEventSource', [
            {
            id:2,
            title: '休',
            start: '2021-04-04',
            },
            {
            id:3,
            title: '休',
            start: '2021-04-11',
            },
            {
            id:4,
            title: '休',
            start: '2021-04-18',
            },
            {
            id:5,
            title: '休',
            start: '2021-04-25',
            }
        ]);
        }
    });

    $("#set_monday").on("click", function (evt) {
        chk_status = $(this).prop('checked');
        
        if (!chk_status) {
        $('#calendar').fullCalendar("removeEvents", 6);
        $('#calendar').fullCalendar("removeEvents", 7);
        $('#calendar').fullCalendar("removeEvents", 8);
        $('#calendar').fullCalendar("removeEvents", 9);
        } else {
        $('#calendar').fullCalendar('addEventSource', [
            {
            id:6,
            title: '休',
            start: '2021-04-05',
            },
            {
            id:7,
            title: '休',
            start: '2021-04-12',
            },
            {
            id:8,
            title: '休',
            start: '2021-04-19',
            },
            {
            id:9,
            title: '休',
            start: '2021-04-26',
            }
        ]);
        }
    });

});



</script>

</html>
