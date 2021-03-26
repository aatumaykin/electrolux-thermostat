electrolux-thermostat

наработки по эмуляции приложения home comfort для управления конвектором electrolux

1. copy .env.dist to .env
2. fill params
3. run `composer install`
4. `symfony serve`


# HTTP запросы
->

    POST /api/userAuth
    Host: dongle.rusklimat.ru
    User-Agent: okhttp/4.3.1

    {"appcode":"electrolux","login":"","password":""}

<-

    {
      "result": {
        "token": "a3d82d6ea0fb5f67236575564d2258a7d4a8a0ad",
        "user": {
          "name": ""
        },
        "enc_key": "vBdLZSJ9k/B1pDlBSatjWZZMVT90hDC8KFaVu+rvMyQ=",
        "server": {
          "host": "dongle.rusklimat.ru",
          "port": "10001"
        },
        "tcp_server": {
          "host": "dongle.rusklimat.ru",
          "port": "10040"
        },
        "versions": [
          "v.0.0.2",
          "v.0.0.3"
        ]
      },
      "error_code": "0",
      "error_message": ""
    }

# примеры запросов по TCP

<-
  
    {"command":"ask","data":"token"}

->
    
    {"lang":"ru","command":"token","data":"a3d82d6ea0fb5f67236575564d2258a7d4a8a0ad"}

<-
    
    {"message_id":"a","command":"token","result":{"result":"93938"},"message":""}

<-

    {"message_id":"f","command":"getDeviceParams","result":{"device":[{"state":1,"child_lock":"0","sensor_fault":"0","window_open":"0","mute":"0","window_opened":0,"calendar_on":"0","brightness":"1","led_off_auto":0,"temp_comfort":10,"delta_eco":4,"temp_antifrost":7,"mode":1,"mode_temp_1":"0","mode_temp_2":"0","mode_temp_3":"0","hours":12,"minutes":0,"timer":0,"current_temp":7,"heat_mode":0,"power":0,"code":"0","lcd_on":1,"time_seconds":8,"time_minutes":41,"time_hour":16,"time_day":26,"time_month":3,"time_year":21,"time_weekday":5,"preset_monday":0,"preset_tuesday":0,"preset_wednesday":0,"preset_thursday":0,"preset_friday":0,"preset_saturday":0,"preset_sunday":0,"preset_day_1":0,"preset_day_2":0,"preset_day_3":0,"preset_day_4":0,"preset_day_5":0,"preset_day_6":0,"preset_day_7":0,"preset_day_8":2,"preset_day_9":2,"preset_day_10":2,"preset_day_11":2,"preset_day_12":2,"preset_day_13":2,"preset_day_14":2,"preset_day_15":2,"preset_day_16":2,"preset_day_17":2,"preset_day_18":2,"preset_day_19":2,"preset_day_20":2,"preset_day_21":2,"preset_day_22":2,"preset_day_23":2,"preset_day_24":0,"tempid":"188577","uid":"188577","mac":"set","room":"баня","sort":0,"type":"convector24","curr_slot":"0","active_slot":0,"slop":"0","curr_scene":"0","curr_scene_id":0,"wait_slot":0,"curr_slot_dropped":0,"curr_scene_dropped":"0","online":0,"lock":0}],"invalid":[],"waiting":[],"invalid_device":[],"waiting_device":[]},"message":""}

<-

    {"command":"changedDeviceParams","data":{"188577":{"state":1,"child_lock":"0","sensor_fault":"0","window_open":"0","mute":"0","window_opened":0,"calendar_on":"0","brightness":"1","led_off_auto":0,"temp_comfort":10,"delta_eco":4,"temp_antifrost":7,"mode":1,"mode_temp_1":"0","mode_temp_2":"0","mode_temp_3":"0","hours":12,"minutes":0,"timer":0,"current_temp":9,"heat_mode":0,"power":0,"code":"0","lcd_on":1,"time_seconds":46,"time_minutes":54,"time_hour":15,"time_day":26,"time_month":3,"time_year":21,"time_weekday":5,"preset_monday":0,"preset_tuesday":0,"preset_wednesday":0,"preset_thursday":0,"preset_friday":0,"preset_saturday":0,"preset_sunday":0,"preset_day_1":0,"preset_day_2":0,"preset_day_3":0,"preset_day_4":0,"preset_day_5":0,"preset_day_6":0,"preset_day_7":0,"preset_day_8":2,"preset_day_9":2,"preset_day_10":2,"preset_day_11":2,"preset_day_12":2,"preset_day_13":2,"preset_day_14":2,"preset_day_15":2,"preset_day_16":2,"preset_day_17":2,"preset_day_18":2,"preset_day_19":2,"preset_day_20":2,"preset_day_21":2,"preset_day_22":2,"preset_day_23":2,"preset_day_24":0,"tempid":"188577","uid":"188577","mac":"set","room":"баня","sort":0,"type":"convector24","curr_slot":"0","active_slot":0,"slop":"0","curr_scene":"0","curr_scene_id":0,"wait_slot":0,"curr_slot_dropped":0,"curr_scene_dropped":"0","online":1,"lock":0}}}

->

    {"lang":"ru","command":"setDeviceParams","message_id":null,"data":{"device":[{"uid":"188577", "params":{"state":"0"}}]}}

<-

    {"message_id":"","command":"setDeviceParams","result":true,"message":""}

# Использование команд

    # авторизоваться (можно не использовать)
    bin/console login
 
    # получить информацию об устройствах (автоматически логинится)
    bin/console get-devices

    # сработало один раз (автоматически логинится)
    bin/console update-device 188577 '{"state":"1"}'

    # раскодировать сообщение
    bin/console decode vBdLZSJ9k/B1pDlBSatjWZZMVT90hDC8KFaVu+rvMyQ= vrswNYrl4Pb4KeQCiRBbGNjF7Jdy7J1rWBB7cuSJKpaL6JigXm6k/CvLjmH818Vt18VDb2dsjcxFV7ZG3zb3ls1+0759khtHUM+HU81vvxY=7aa0f4722a38055e5d5ba20cbb71aaa5

    # раскодировать сообщение в hex формате
    bin/console decode --hex vBdLZSJ9k/B1pDlBSatjWZZMVT90hDC8KFaVu+rvMyQ= 767273774e59726c345062344b65514369524262474e6a46374a6479374a3172574242376375534a4b70614c364a6967586d366b2f43764c6a6d48383138567431385644623264736a63784656375a47337a62336c73312b303735396b687448554d2b48553831767678593d3761613066343732326133383035356535643562613230636262373161616135

    # раскодировать сообщения через файл обмена. принцип работы:
    # - запускаем команду с указанием файла
    # - записываем в файл кодированное сообщение 1
    # - записываем в файл кодированное сообщение 2
    # - и т.д.
    bin/console decode --file $(pwd)/messages.txt vBdLZSJ9k/B1pDlBSatjWZZMVT90hDC8KFaVu+rvMyQ=
