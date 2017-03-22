# Map

## 1 - To get a variable
Instead of use property_get, use eval with $GLOBALS

Ex:
```xml
$i
$GLOBALS['IDE_EVAL_CACHE']['80cb5888-d72e-43d3-97ba-ac23a7f848ce']=$i
<- eval -i 1 -- JEdMT0JBTFNbJ0lERV9FVkFMX0NBQ0hFJ11bJzgwY2I1ODg4LWQ3MmUtNDNkMy05N2JhLWFjMjNhN2Y4NDhjZSddPSRp
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="eval" transaction_id="1">
     <property type="int"><![CDATA[1]]></property>
   </response>
```

## 2 - Set a variable
Send eval with command (Ex: $i = 2).
Get context_names
Get context_get for each context_names returned
```xml
$GLOBALS['IDE_EVAL_CACHE']['6ba782f2-907d-4b7b-be45-16a513daf661']=$i = 2
<- eval -i 1 -- JEdMT0JBTFNbJ0lERV9FVkFMX0NBQ0hFJ11bJzZiYTc4MmYyLTkwN2QtNGI3Yi1iZTQ1LTE2YTUxM2RhZjY2MSddPSRpID0gMg==
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="eval" transaction_id="1">
    <property type="int"><![CDATA[2]]></property>
   </response>

<- context_names -i 20 -d 0
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="context_names" transaction_id="20">
    <context name="Locals" id="0"></context><context name="Superglobals" id="1"></context><context name="User defined constants" id="2"></context>
   </response>

<- context_get -i 21 -d 0 -c 0
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="context_get" transaction_id="21" context="0">
    <property name="$IDE_EVAL_CACHE" fullname="$IDE_EVAL_CACHE" type="array" children="1" numchildren="2" page="0" pagesize="100">
    <property name="80cb5888-d72e-43d3-97ba-ac23a7f848ce" fullname="$IDE_EVAL_CACHE[&#39;80cb5888-d72e-43d3-97ba-ac23a7f848ce&#39;]" type="int"><![CDATA[1]]></property>
    <property name="6ba782f2-907d-4b7b-be45-16a513daf661" fullname="$IDE_EVAL_CACHE[&#39;6ba782f2-907d-4b7b-be45-16a513daf661&#39;]" type="int"><![CDATA[2]]></property>
    </property><property name="$i" fullname="$i" type="int"><![CDATA[2]]></property>
   </response>

<- context_get -i 22 -d 0 -c 1
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="context_get" transaction_id="22" context="1">
    <property name="$_COOKIE" fullname="$_COOKIE" type="array" children="0" numchildren="0" page="0" pagesize="100"></property>
    <property name="$_ENV" fullname="$_ENV" type="array" children="0" numchildren="0" page="0" pagesize="100"></property>
    <property name="$_FILES" fullname="$_FILES" type="array" children="0" numchildren="0" page="0" pagesize="100"></property>
    <property name="$_GET" fullname="$_GET" type="array" children="0" numchildren="0" page="0" pagesize="100"></property>
    <property name="$_POST" fullname="$_POST" type="array" children="0" numchildren="0" page="0" pagesize="100"></property>
    <property name="$_REQUEST" fullname="$_REQUEST" type="array" children="0" numchildren="0" page="0" pagesize="100"></property>
    <property name="$_SERVER" fullname="$_SERVER" type="array" children="1" numchildren="72" page="0" pagesize="100">
        <property name="PATH" fullname="$_SERVER['PATH']" type="string" size="122" encoding="base64"><![CDATA[L3Vzci9sb2NhbC9zYmluOi91c3IvbG9jYWwvYmluOi91c3Ivc2JpbjovdXNyL2Jpbjovc2JpbjovYmluOi91c3IvZ2FtZXM6L3Vzci9sb2NhbC9nYW1lczovc25hcC9iaW46L2hvbWUvdGFjbm9tYW4vLnJ2bS9iaW4=]]></property>
        <property name="XAUTHORITY" fullname="$_SERVER['XAUTHORITY']" type="string" size="26" encoding="base64"><![CDATA[L2hvbWUvdGFjbm9tYW4vLlhhdXRob3JpdHk=]]></property>
        <property name="XMODIFIERS" fullname="$_SERVER['XMODIFIERS']" type="string" size="8" encoding="base64"><![CDATA[QGltPWlidXM=]]></property>
        <property name="XDG_DATA_DIRS" fullname="$_SERVER['XDG_DATA_DIRS']" type="string" size="87" encoding="base64"><![CDATA[L3Vzci9zaGFyZS91YnVudHU6L3Vzci9zaGFyZS9nbm9tZTovdXNyL2xvY2FsL3NoYXJlLzovdXNyL3NoYXJlLzovdmFyL2xpYi9zbmFwZC9kZXNrdG9w]]></property>
        <property name="GDMSESSION" fullname="$_SERVER['GDMSESSION']" type="string" size="6" encoding="base64"><![CDATA[dWJ1bnR1]]></property>
        <property name="MANDATORY_PATH" fullname="$_SERVER['MANDATORY_PATH']" type="string" size="38" encoding="base64"><![CDATA[L3Vzci9zaGFyZS9nY29uZi91YnVudHUubWFuZGF0b3J5LnBhdGg=]]></property>
        <property name="GTK_IM_MODULE" fullname="$_SERVER['GTK_IM_MODULE']" type="string" size="4" encoding="base64"><![CDATA[aWJ1cw==]]></property>
        <property name="DBUS_SESSION_BUS_ADDRESS" fullname="$_SERVER['DBUS_SESSION_BUS_ADDRESS']" type="string" size="34" encoding="base64"><![CDATA[dW5peDphYnN0cmFjdD0vdG1wL2RidXMtcUVwMDZWbW5vUw==]]></property>
        <property name="DEFAULTS_PATH" fullname="$_SERVER['DEFAULTS_PATH']" type="string" size="36" encoding="base64"><![CDATA[L3Vzci9zaGFyZS9nY29uZi91YnVudHUuZGVmYXVsdC5wYXRo]]></property>
        <property name="XDG_CURRENT_DESKTOP" fullname="$_SERVER['XDG_CURRENT_DESKTOP']" type="string" size="5" encoding="base64"><![CDATA[VW5pdHk=]]></property>
        <property name="LD_LIBRARY_PATH" fullname="$_SERVER['LD_LIBRARY_PATH']" type="string" size="49" encoding="base64"><![CDATA[L2hvbWUvdGFjbm9tYW4vLnBocHN0b3JtL1BocFN0b3JtLTE0NS4xNjE2LjMvYmluOg==]]></property>
        <property name="UPSTART_SESSION" fullname="$_SERVER['UPSTART_SESSION']" type="string" size="51" encoding="base64"><![CDATA[dW5peDphYnN0cmFjdD0vY29tL3VidW50dS91cHN0YXJ0LXNlc3Npb24vMTAwMC8yOTUz]]></property>
        <property name="QT4_IM_MODULE" fullname="$_SERVER['QT4_IM_MODULE']" type="string" size="3" encoding="base64"><![CDATA[eGlt]]></property>
        <property name="SESSION_MANAGER" fullname="$_SERVER['SESSION_MANAGER']" type="string" size="97" encoding="base64"><![CDATA[bG9jYWwvdGFjbm9tYW4tSW5zcGlyb24tNzM0ODpAL3RtcC8uSUNFLXVuaXgvMzM3MSx1bml4L3RhY25vbWFuLUluc3Bpcm9uLTczNDg6L3RtcC8uSUNFLXVuaXgvMzM3MQ==]]></property>
        <property name="QT_LINUX_ACCESSIBILITY_ALWAYS_ON" fullname="$_SERVER['QT_LINUX_ACCESSIBILITY_ALWAYS_ON']" type="string" size="1" encoding="base64"><![CDATA[MQ==]]></property>
        <property name="LOGNAME" fullname="$_SERVER['LOGNAME']" type="string" size="8" encoding="base64"><![CDATA[dGFjbm9tYW4=]]></property>
        <property name="XDEBUG_CONFIG" fullname="$_SERVER['XDEBUG_CONFIG']" type="string" size="12" encoding="base64"><![CDATA[aWRla2V5PTE2MzAz]]></property>
        <property name="JOB" fullname="$_SERVER['JOB']" type="string" size="21" encoding="base64"><![CDATA[dW5pdHktc2V0dGluZ3MtZGFlbW9u]]></property>
        <property name="PWD" fullname="$_SERVER['PWD']" type="string" size="14" encoding="base64"><![CDATA[L2hvbWUvdGFjbm9tYW4=]]></property>
        <property name="IM_CONFIG_PHASE" fullname="$_SERVER['IM_CONFIG_PHASE']" type="string" size="1" encoding="base64"><![CDATA[MQ==]]></property>
        <property name="LANGUAGE" fullname="$_SERVER['LANGUAGE']" type="string" size="6" encoding="base64"><![CDATA[cHRfQlI6]]></property>
        <property name="SHELL" fullname="$_SERVER['SHELL']" type="string" size="8" encoding="base64"><![CDATA[L2Jpbi96c2g=]]></property>
        <property name="GIO_LAUNCHED_DESKTOP_FILE" fullname="$_SERVER['GIO_LAUNCHED_DESKTOP_FILE']" type="string" size="67" encoding="base64"><![CDATA[L2hvbWUvdGFjbm9tYW4vLmxvY2FsL3NoYXJlL2FwcGxpY2F0aW9ucy9qZXRicmFpbnMtcGhwc3Rvcm0uZGVza3RvcA==]]></property>
        <property name="GTK2_MODULES" fullname="$_SERVER['GTK2_MODULES']" type="string" size="17" encoding="base64"><![CDATA[b3ZlcmxheS1zY3JvbGxiYXI=]]></property>
        <property name="INSTANCE" fullname="$_SERVER['INSTANCE']" type="string" size="0" encoding="base64"><![CDATA[]]></property>
        <property name="GNOME_DESKTOP_SESSION_ID" fullname="$_SERVER['GNOME_DESKTOP_SESSION_ID']" type="string" size="18" encoding="base64"><![CDATA[dGhpcy1pcy1kZXByZWNhdGVk]]></property>
        <property name="UPSTART_INSTANCE" fullname="$_SERVER['UPSTART_INSTANCE']" type="string" size="0" encoding="base64"><![CDATA[]]></property>
        <property name="GTK_MODULES" fullname="$_SERVER['GTK_MODULES']" type="string" size="32" encoding="base64"><![CDATA[Z2FpbDphdGstYnJpZGdlOnVuaXR5LWd0ay1tb2R1bGU=]]></property>
        <property name="CLUTTER_IM_MODULE" fullname="$_SERVER['CLUTTER_IM_MODULE']" type="string" size="3" encoding="base64"><![CDATA[eGlt]]></property>
        <property name="XDG_SESSION_PATH" fullname="$_SERVER['XDG_SESSION_PATH']" type="string" size="40" encoding="base64"><![CDATA[L29yZy9mcmVlZGVza3RvcC9EaXNwbGF5TWFuYWdlci9TZXNzaW9uMA==]]></property>
        <property name="COMPIZ_BIN_PATH" fullname="$_SERVER['COMPIZ_BIN_PATH']" type="string" size="9" encoding="base64"><![CDATA[L3Vzci9iaW4v]]></property>
        <property name="SESSIONTYPE" fullname="$_SERVER['SESSIONTYPE']" type="string" size="13" encoding="base64"><![CDATA[Z25vbWUtc2Vzc2lvbg==]]></property>
        <property name="XDG_SESSION_DESKTOP" fullname="$_SERVER['XDG_SESSION_DESKTOP']" type="string" size="6" encoding="base64"><![CDATA[dWJ1bnR1]]></property>
        <property name="SHLVL" fullname="$_SERVER['SHLVL']" type="string" size="1" encoding="base64"><![CDATA[MA==]]></property>
        <property name="COMPIZ_CONFIG_PROFILE" fullname="$_SERVER['COMPIZ_CONFIG_PROFILE']" type="string" size="6" encoding="base64"><![CDATA[dWJ1bnR1]]></property>
        <property name="QT_IM_MODULE" fullname="$_SERVER['QT_IM_MODULE']" type="string" size="4" encoding="base64"><![CDATA[aWJ1cw==]]></property>
        <property name="UPSTART_JOB" fullname="$_SERVER['UPSTART_JOB']" type="string" size="6" encoding="base64"><![CDATA[dW5pdHk3]]></property>
        <property name="XFILESEARCHPATH" fullname="$_SERVER['XFILESEARCHPATH']" type="string" size="26" encoding="base64"><![CDATA[L3Vzci9kdC9hcHAtZGVmYXVsdHMvJUwvRHQ=]]></property>
        <property name="XDG_CONFIG_DIRS" fullname="$_SERVER['XDG_CONFIG_DIRS']" type="string" size="51" encoding="base64"><![CDATA[L2V0Yy94ZGcveGRnLXVidW50dTovdXNyL3NoYXJlL3Vwc3RhcnQveGRnOi9ldGMveGRn]]></property>
        <property name="LANG" fullname="$_SERVER['LANG']" type="string" size="11" encoding="base64"><![CDATA[cHRfQlIuVVRGLTg=]]></property>
        <property name="GNOME_KEYRING_CONTROL" fullname="$_SERVER['GNOME_KEYRING_CONTROL']" type="string" size="0" encoding="base64"><![CDATA[]]></property>
        <property name="XDG_SEAT_PATH" fullname="$_SERVER['XDG_SEAT_PATH']" type="string" size="37" encoding="base64"><![CDATA[L29yZy9mcmVlZGVza3RvcC9EaXNwbGF5TWFuYWdlci9TZWF0MA==]]></property>
        <property name="XDG_SESSION_ID" fullname="$_SERVER['XDG_SESSION_ID']" type="string" size="2" encoding="base64"><![CDATA[YzI=]]></property>
        <property name="XDG_SESSION_TYPE" fullname="$_SERVER['XDG_SESSION_TYPE']" type="string" size="3" encoding="base64"><![CDATA[eDEx]]></property>
        <property name="DISPLAY" fullname="$_SERVER['DISPLAY']" type="string" size="2" encoding="base64"><![CDATA[OjA=]]></property>
        <property name="GDM_LANG" fullname="$_SERVER['GDM_LANG']" type="string" size="5" encoding="base64"><![CDATA[cHRfQlI=]]></property>
        <property name="XDG_GREETER_DATA_DIR" fullname="$_SERVER['XDG_GREETER_DATA_DIR']" type="string" size="30" encoding="base64"><![CDATA[L3Zhci9saWIvbGlnaHRkbS1kYXRhL3RhY25vbWFu]]></property>
        <property name="UPSTART_EVENTS" fullname="$_SERVER['UPSTART_EVENTS']" type="string" size="16" encoding="base64"><![CDATA[eHNlc3Npb24gc3RhcnRlZA==]]></property>
        <property name="GPG_AGENT_INFO" fullname="$_SERVER['GPG_AGENT_INFO']" type="string" size="37" encoding="base64"><![CDATA[L2hvbWUvdGFjbm9tYW4vLmdudXBnL1MuZ3BnLWFnZW50OjA6MQ==]]></property>
        <property name="DESKTOP_SESSION" fullname="$_SERVER['DESKTOP_SESSION']" type="string" size="6" encoding="base64"><![CDATA[dWJ1bnR1]]></property>
        <property name="SESSION" fullname="$_SERVER['SESSION']" type="string" size="6" encoding="base64"><![CDATA[dWJ1bnR1]]></property>
        <property name="USER" fullname="$_SERVER['USER']" type="string" size="8" encoding="base64"><![CDATA[dGFjbm9tYW4=]]></property>
        <property name="XDG_MENU_PREFIX" fullname="$_SERVER['XDG_MENU_PREFIX']" type="string" size="6" encoding="base64"><![CDATA[Z25vbWUt]]></property>
        <property name="GIO_LAUNCHED_DESKTOP_FILE_PID" fullname="$_SERVER['GIO_LAUNCHED_DESKTOP_FILE_PID']" type="string" size="5" encoding="base64"><![CDATA[MTYyMTE=]]></property>
        <property name="QT_ACCESSIBILITY" fullname="$_SERVER['QT_ACCESSIBILITY']" type="string" size="1" encoding="base64"><![CDATA[MQ==]]></property>
        <property name="SSH_AUTH_SOCK" fullname="$_SERVER['SSH_AUTH_SOCK']" type="string" size="26" encoding="base64"><![CDATA[L3J1bi91c2VyLzEwMDAva2V5cmluZy9zc2g=]]></property>
        <property name="XDG_SEAT" fullname="$_SERVER['XDG_SEAT']" type="string" size="5" encoding="base64"><![CDATA[c2VhdDA=]]></property>
        <property name="NLSPATH" fullname="$_SERVER['NLSPATH']" type="string" size="29" encoding="base64"><![CDATA[L3Vzci9kdC9saWIvbmxzL21zZy8lTC8lTi5jYXQ=]]></property>
        <property name="QT_QPA_PLATFORMTHEME" fullname="$_SERVER['QT_QPA_PLATFORMTHEME']" type="string" size="11" encoding="base64"><![CDATA[YXBwbWVudS1xdDU=]]></property>
        <property name="XDG_VTNR" fullname="$_SERVER['XDG_VTNR']" type="string" size="1" encoding="base64"><![CDATA[Nw==]]></property>
        <property name="XDG_RUNTIME_DIR" fullname="$_SERVER['XDG_RUNTIME_DIR']" type="string" size="14" encoding="base64"><![CDATA[L3J1bi91c2VyLzEwMDA=]]></property>
        <property name="HOME" fullname="$_SERVER['HOME']" type="string" size="14" encoding="base64"><![CDATA[L2hvbWUvdGFjbm9tYW4=]]></property>
        <property name="GNOME_KEYRING_PID" fullname="$_SERVER['GNOME_KEYRING_PID']" type="string" size="0" encoding="base64"><![CDATA[]]></property>
        <property name="PHP_SELF" fullname="$_SERVER['PHP_SELF']" type="string" size="26" encoding="base64"><![CDATA[L3Zhci93d3cvQmluZ29Eb2NzL2pvYi5waHA=]]></property>
        <property name="SCRIPT_NAME" fullname="$_SERVER['SCRIPT_NAME']" type="string" size="26" encoding="base64"><![CDATA[L3Zhci93d3cvQmluZ29Eb2NzL2pvYi5waHA=]]></property>
        <property name="SCRIPT_FILENAME" fullname="$_SERVER['SCRIPT_FILENAME']" type="string" size="26" encoding="base64"><![CDATA[L3Zhci93d3cvQmluZ29Eb2NzL2pvYi5waHA=]]></property>
        <property name="PATH_TRANSLATED" fullname="$_SERVER['PATH_TRANSLATED']" type="string" size="26" encoding="base64"><![CDATA[L3Zhci93d3cvQmluZ29Eb2NzL2pvYi5waHA=]]></property>
        <property name="DOCUMENT_ROOT" fullname="$_SERVER['DOCUMENT_ROOT']" type="string" size="0" encoding="base64"><![CDATA[]]></property>
        <property name="REQUEST_TIME_FLOAT" fullname="$_SERVER['REQUEST_TIME_FLOAT']" type="float"><![CDATA[1489955996.3682]]></property>
        <property name="REQUEST_TIME" fullname="$_SERVER['REQUEST_TIME']" type="int"><![CDATA[1489955996]]></property>
        <property name="argv" fullname="$_SERVER['argv']" type="array" children="1" numchildren="1"></property>
        <property name="argc" fullname="$_SERVER['argc']" type="int"><![CDATA[1]]></property>
    </property>
    <property name="$GLOBALS" fullname="$GLOBALS" type="array" children="1" numchildren="12" page="0" pagesize="100">
        <property name="_GET" fullname="$GLOBALS['_GET']" type="array" children="0" numchildren="0"></property>
        <property name="_POST" fullname="$GLOBALS['_POST']" type="array" children="0" numchildren="0"></property>
        <property name="_COOKIE" fullname="$GLOBALS['_COOKIE']" type="array" children="0" numchildren="0"></property>
        <property name="_FILES" fullname="$GLOBALS['_FILES']" type="array" children="0" numchildren="0"></property>
        <property name="argv" fullname="$GLOBALS['argv']" type="array" children="1" numchildren="1"></property>
        <property name="argc" fullname="$GLOBALS['argc']" type="int"><![CDATA[1]]></property>
        <property name="_ENV" fullname="$GLOBALS['_ENV']" type="array" children="0" numchildren="0"></property>
        <property name="_REQUEST" fullname="$GLOBALS['_REQUEST']" type="array" children="0" numchildren="0"></property>
        <property name="_SERVER" fullname="$GLOBALS['_SERVER']" type="array" children="1" numchildren="72"></property>
        <property name="i" fullname="$GLOBALS['i']" type="int"><![CDATA[2]]></property>
        <property name="GLOBALS" fullname="$GLOBALS['GLOBALS']" type="array" children="1" recursive="1"></property>
        <property name="IDE_EVAL_CACHE" fullname="$GLOBALS['IDE_EVAL_CACHE']" type="array" children="1" numchildren="2"></property>
    </property>
</response>

<- context_get -i 23 -d 0 -c 2
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="context_get" transaction_id="23" context="2"></response>
```

## 3 - Calling a function
Ex: str_repeat('a', 20)

```xml
$GLOBALS['IDE_EVAL_CACHE']['529be443-6611-45a1-ae56-213aaf129aa8']=str_repeat('a', 20)

<- eval -i 24 -- JEdMT0JBTFNbJ0lERV9FVkFMX0NBQ0hFJ11bJzUyOWJlNDQzLTY2MTEtNDVhMS1hZTU2LTIxM2FhZjEyOWFhOCddPXN0cl9yZXBlYXQoJ2EnLCAyMCk=
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="eval" transaction_id="24"><property type="string" size="20" encoding="base64"><![CDATA[YWFhYWFhYWFhYWFhYWFhYWFhYWE=]]></property></response>
```

## 4 - Calling a function with pointer
Ex: preg_match('/a/', 'ba', $result)
```xml
$GLOBALS['IDE_EVAL_CACHE']['bd4886ee-b983-48b3-8c49-aecfebf6dfe0']=preg_match('/a/', 'ba', $result)

<- eval -i 25 -- JEdMT0JBTFNbJ0lERV9FVkFMX0NBQ0hFJ11bJ2JkNDg4NmVlLWI5ODMtNDhiMy04YzQ5LWFlY2ZlYmY2ZGZlMCddPXByZWdfbWF0Y2goJy9hLycsICdiYScsICRyZXN1bHQp
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="eval" transaction_id="25"><property type="int"><![CDATA[1]]></property></response>
```

### 4.1 - Getting $result

```xml
$GLOBALS['IDE_EVAL_CACHE']['36b115a2-5578-4faa-84c8-8b041fb6eebb']=$result

<- eval -i 26 -- JEdMT0JBTFNbJ0lERV9FVkFMX0NBQ0hFJ11bJzM2YjExNWEyLTU1NzgtNGZhYS04NGM4LThiMDQxZmI2ZWViYiddPSRyZXN1bHQ=
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="eval" transaction_id="26"><property type="array" children="1" numchildren="1" page="0" pagesize="100"><property name="0" type="string" size="1" encoding="base64"><![CDATA[YQ==]]></property></property></response>

$GLOBALS['IDE_EVAL_CACHE']['e8b88bd8-a8d6-43d1-b7f3-d4fc3ae30051']=var_export($GLOBALS['IDE_EVAL_CACHE']['36b115a2-5578-4faa-84c8-8b041fb6eebb'], true)

<- eval -i 27 -- JEdMT0JBTFNbJ0lERV9FVkFMX0NBQ0hFJ11bJ2U4Yjg4YmQ4LWE4ZDYtNDNkMS1iN2YzLWQ0ZmMzYWUzMDA1MSddPXZhcl9leHBvcnQoJEdMT0JBTFNbJ0lERV9FVkFMX0NBQ0hFJ11bJzM2YjExNWEyLTU1NzgtNGZhYS04NGM4LThiMDQxZmI2ZWViYiddLCB0cnVlKQ==
-> <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="eval" transaction_id="27"><property type="string" size="21" encoding="base64"><![CDATA[YXJyYXkgKAogIDAgPT4gJ2EnLAop]]></property></response>
```
