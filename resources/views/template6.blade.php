<html>
<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <style type="text/css">
        span.cls_002 {
            font-family: "DejaVu Serif", serif;
            font-size: 12.6px;
            color: rgb(0, 0, 0);
            font-weight: normal;
            font-style: normal;
            text-decoration: none
        }

        div.cls_002 {
            font-family: "DejaVu Serif", serif;
            font-size: 12.6px;
            color: rgb(0, 0, 0);
            font-weight: normal;
            font-style: normal;
            text-decoration: none
        }

        span.cls_003 {
            font-family: "DejaVu Serif", serif;
            font-size: 12.6px;
            color: rgb(0, 0, 0);
            font-weight: normal;
            font-style: normal;
            text-decoration: none
        }

        div.cls_003 {
            font-family: "DejaVu Serif", serif;
            font-size: 12.6px;
            color: rgb(0, 0, 0);
            font-weight: normal;
            font-style: normal;
            text-decoration: none
        }

        span.cls_004 {
            font-family: "DejaVu Serif", serif;
            font-size: 12.6px;
            color: rgb(0, 0, 0);
            font-weight: bold;
            font-style: normal;
            text-decoration: none
        }

        div.cls_004 {
            font-family: "DejaVu Serif", serif;
            font-size: 12.6px;
            color: rgb(0, 0, 0);
            font-weight: bold;
            font-style: normal;
            text-decoration: none
        }

        span.cls_005 {
            font-family: "DejaVu Serif", serif;
            font-size: 10.2px;
            color: rgb(0, 0, 0);
            font-weight: normal;
            font-style: normal;
            text-decoration: none
        }

        div.cls_005 {
            font-family: "DejaVu Serif", serif;
            font-size: 10.2px;
            color: rgb(0, 0, 0);
            font-weight: normal;
            font-style: normal;
            text-decoration: none
        }
    </style>
</head>
</head>
<body>
<div style="left:50%;top:0px;width:655px;height:940px; overflow:hidden">
    <div style="left:0px;top:0px">
        <div align="right" style="margin-top: 20px">
            <div class="cls_002"><span class="cls_002">Приложение 9</span></div>
            <div class="cls_002"><span class="cls_002">к приказу Министра здравоохранения и</span></div>
            <div class="cls_002"><span class="cls_002">социального развития Республики Казахстан,</span></div>
            <div class="cls_002"><span class="cls_002">в которые вносят изменения и дополнения</span></div>
        </div>

        <div style="text-align: center; margin: 10px 0;" class="cls_003"><span class="cls_003">СПРАВКА</span></div>

        <div style="margin-left: 100px">
            <div class="cls_002"><span class="cls_002">Дана гр. </span><span
                    class="cls_004">{{$data->full_name}} {{$data->birth_date}}</span><span
                    class="cls_002"> г.р.</span></div>
            <div class="cls_005"><span class="cls_005">(Ф.И.О. обучающегося/студента, с указанием года рождения)</span>
            </div>
            <div class="cls_002"><span class="cls_002">В том, что он(а) действительно является обучающимся</span></div>
            <div class="cls_002"><span class="cls_002">АО Международного университета информационных технологий</span>
            </div>
            <div class="cls_002"><span class="cls_002">{{$data->specialty_code}}»-{{$data->specialty_name}}</span></div>
            <div class="cls_002"><span
                    class="cls_002">Госуд.лицензия Серия АБ № 0064060 от 29.05.2009 год без ограничения срока</span>
            </div>
            <div class="cls_002"><span class="cls_002">4 класса/курса, форма обучения-очное.</span></div>
            <div class="cls_002"><span class="cls_002">Справка действительна на {{$data->current_year}} учебный год, с 1 сентября 2018 г. по 31</span>
            </div>
            <div class="cls_002"><span class="cls_002">августа 2022 г.</span></div>
            <div class="cls_002"><span class="cls_002">Справка выдана для предъявления в отделение</span></div>
            <div class="cls_002"><span class="cls_002">Государственной корпораций</span></div>
            <div class="cls_002"><span
                    class="cls_002">Срок обучения в учебном заведении {{$data->course_count}} года</span></div>
            <div class="cls_002"><span class="cls_002">Период обучения с {{$data->start_date}} г. по {{$data->end_date}} г.</span>
            </div>
            <div class="cls_004" style="margin-top: 20px"><span class="cls_004">Примечание:</span><span
                    class="cls_002"> справка действительна 1 год.</span></div>
            <div class="cls_002"><span
                    class="cls_002">В случае отчисления обучающегося из учебного заведения или перевода на</span>
            </div>
            <div class="cls_002"><span class="cls_002">заочную форму обучения, руководитель учебного заведения извещает отделение</span>
            </div>
            <div class="cls_002"><span
                    class="cls_002">Государственной корпораций по месту жительства получателя пособия.</span></div>

            <div style="left:103.77px;top:626.33px; margin-top: 30px; margin-left: 20px;" class="cls_004"><span
                    style="margin-right: 200px;" class="cls_004">Проректор по АиВД</span> <span
                    class="cls_004">{{$data->vice_rector_of_aivd_name}}</span></div>
            <div style="left:81.27px;top:771.05px; margin-top: 30px;" class="cls_005"><span
                    class="cls_005">Исп: {{$data->executor_name}}</span>
                <img style="margin-left: 320px;"
                    src="data:image/png;base64, {!! base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(125)->generate($data->file_url)) !!} ">
            </div>
            <div style="left:81.27px;top:781.61px" class="cls_005"><span
                    class="cls_005">Тел.: {{$data->phone_number}}</span></div>
        </div>
    </div>
</body>
</html>
