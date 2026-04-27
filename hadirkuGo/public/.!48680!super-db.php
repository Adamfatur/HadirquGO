<?php
/** Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 4.17.1
*/$ia="4.17.1";function
adminer_errors($Hc,$Jc){return!!preg_match('~^(Trying to access array offset on( value of type)? null|Undefined (array key|property))~',$Jc);}error_reporting(6135);set_error_handler('adminer_errors',E_WARNING);$dd=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($dd||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Hi=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Hi)$$X=$Hi;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection(){global$g;return$g;}function
adminer(){global$b;return$b;}function
version(){global$ia;return$ia;}function
idf_unescape($u){if(!preg_match('~^[`\'"[]~',$u))return$u;$re=substr($u,-1);return
str_replace($re.$re,$re,substr($u,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
number($X){return
preg_replace('~[^0-9]+~','',$X);}function
number_type(){return'((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';}function
remove_slashes($rg,$dd=false){if(function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()){while(list($y,$X)=each($rg)){foreach($X
as$ie=>$W){unset($rg[$y][$ie]);if(is_array($W)){$rg[$y][stripslashes($ie)]=$W;$rg[]=&$rg[$y][stripslashes($ie)];}else$rg[$y][stripslashes($ie)]=($dd?$W:stripslashes($W));}}}}function
bracket_escape($u,$Na=false){static$si=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($u,($Na?array_flip($si):$si));}function
min_version($Yi,$De="",$h=null){global$g;if(!$h)$h=$g;$kh=$h->server_info;if($De&&preg_match('~([\d.]+)-MariaDB~',$kh,$B)){$kh=$B[1];$Yi=$De;}return$Yi&&version_compare($kh,$Yi)>=0;}function
charset($g){return(min_version("5.5.3",0,$g)?"utf8mb4":"utf8");}function
script($wh,$ri="\n"){return"<script".nonce().">$wh</script>$ri";}function
script_src($Mi){return"<script src='".h($Mi)."'".nonce()."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($P){return
str_replace("\0","&#0;",htmlspecialchars($P,ENT_QUOTES,'utf-8'));}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($C,$Y,$gb,$ne="",$vf="",$kb="",$oe=""){$I="<input type='checkbox' name='$C' value='".h($Y)."'".($gb?" checked":"").($oe?" aria-labelledby='$oe'":"").">".($vf?script("qsl('input').onclick = function () { $vf };",""):"");return($ne!=""||$kb?"<label".($kb?" class='$kb'":"").">$I".h($ne)."</label>":$I);}function
optionlist($D,$ch=null,$Qi=false){$I="";foreach($D
as$ie=>$W){$Af=array($ie=>$W);if(is_array($W)){$I.='<optgroup label="'.h($ie).'">';$Af=$W;}foreach($Af
as$y=>$X)$I.='<option'.($Qi||is_string($y)?' value="'.h($y).'"':'').($ch!==null&&($Qi||is_string($y)?(string)$y:$X)===$ch?' selected':'').'>'.h($X);if(is_array($W))$I.='</optgroup>';}return$I;}function
html_select($C,$D,$Y="",$uf=true,$oe=""){if($uf)return"<select name='".h($C)."'".($oe?" aria-labelledby='$oe'":"").">".optionlist($D,$Y)."</select>".(is_string($uf)?script("qsl('select').onchange = function () { $uf };",""):"");$I="";foreach($D
as$y=>$X)$I.="<label><input type='radio' name='".h($C)."' value='".h($y)."'".($y==$Y?" checked":"").">".h($X)."</label>";return$I;}function
confirm($Oe="",$dh="qsl('input')"){return
script("$dh.onclick = function () { return confirm('".($Oe?js_escape($Oe):lang(0))."'); };","");}function
print_fieldset($Kd,$we,$bj=false){echo"<fieldset><legend>","<a href='#fieldset-$Kd'>$we</a>",script("qsl('a').onclick = partial(toggle, 'fieldset-$Kd');",""),"</legend>","<div id='fieldset-$Kd'".($bj?"":" class='hidden'").">\n";}function
bold($Ua,$kb=""){return($Ua?" class='active $kb'":($kb?" class='$kb'":""));}function
js_escape($P){return
addcslashes($P,"\r\n'\\/");}function
ini_bool($Vd){$X=ini_get($Vd);return(preg_match('~^(on|true|yes)$~i',$X)||(int)$X);}function
sid(){static$I;if($I===null)$I=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$I;}function
set_password($Xi,$M,$V,$F){$_SESSION["pwds"][$Xi][$M][$V]=($_COOKIE["adminer_key"]&&is_string($F)?array(encrypt_string($F,$_COOKIE["adminer_key"])):$F);}function
get_password(){$I=get_session("pwds");if(is_array($I))$I=($_COOKIE["adminer_key"]?decrypt_string($I[0],$_COOKIE["adminer_key"]):false);return$I;}function
q($P){global$g;return$g->quote($P);}function
get_vals($G,$d=0){global$g;$I=array();$H=$g->query($G);if(is_object($H)){while($J=$H->fetch_row())$I[]=$J[$d];}return$I;}function
get_key_vals($G,$h=null,$nh=true){global$g;if(!is_object($h))$h=$g;$I=array();$H=$h->query($G);if(is_object($H)){while($J=$H->fetch_row()){if($nh)$I[$J[0]]=$J[1];else$I[]=$J[0];}}return$I;}function
get_rows($G,$h=null,$m="<p class='error'>"){global$g;$Ab=(is_object($h)?$h:$g);$I=array();$H=$Ab->query($G);if(is_object($H)){while($J=$H->fetch_assoc())$I[]=$J;}elseif(!$H&&!is_object($h)&&$m&&(defined("PAGE_HEADER")||$m=="-- "))echo$m.error()."\n";return$I;}function
unique_array($J,$w){foreach($w
as$v){if(preg_match("~PRIMARY|UNIQUE~",$v["type"])){$I=array();foreach($v["columns"]as$y){if(!isset($J[$y]))continue
2;$I[$y]=$J[$y];}return$I;}}}function
escape_key($y){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$y,$B))return$B[1].idf_escape(idf_unescape($B[2])).$B[3];return
idf_escape($y);}function
where($Z,$o=array()){global$g,$x;$I=array();foreach((array)$Z["where"]as$y=>$X){$y=bracket_escape($y,1);$d=escape_key($y);$I[]=$d.($x=="sql"&&$o[$y]["type"]=="json"?" = CAST(".q($X)." AS JSON)":($x=="sql"&&is_numeric($X)&&preg_match('~\.~',$X)?" LIKE ".q($X):($x=="mssql"?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$X)):" = ".unconvert_field($o[$y],q($X)))));if($x=="sql"&&preg_match('~char|text~',$o[$y]["type"])&&preg_match("~[^ -@]~",$X))$I[]="$d = ".q($X)." COLLATE ".charset($g)."_bin";}foreach((array)$Z["null"]as$y)$I[]=escape_key($y)." IS NULL";return
implode(" AND ",$I);}function
where_check($X,$o=array()){parse_str($X,$db);remove_slashes(array(&$db));return
where($db,$o);}function
where_link($t,$d,$Y,$xf="="){return"&where%5B$t%5D%5Bcol%5D=".urlencode($d)."&where%5B$t%5D%5Bop%5D=".urlencode(($Y!==null?$xf:"IS NULL"))."&where%5B$t%5D%5Bval%5D=".urlencode($Y);}function
convert_fields($e,$o,$L=array()){$I="";foreach($e
as$y=>$X){if($L&&!in_array(idf_escape($y),$L))continue;$Ga=convert_field($o[$y]);if($Ga)$I.=", $Ga AS ".idf_escape($y);}return$I;}function
cookie($C,$Y,$ze=2592000){global$ba;return
header("Set-Cookie: $C=".urlencode($Y).($ze?"; expires=".gmdate("D, d M Y H:i:s",time()+$ze)." GMT":"")."; path=".preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]).($ba?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session($kd=false){$Pi=ini_bool("session.use_cookies");if(!$Pi||$kd){session_write_close();if($Pi&&@ini_set("session.use_cookies",false)===false)session_start();}}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$X){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($Xi,$M,$V,$k=null){global$oc;preg_match('~([^?]*)\??(.*)~',remove_from_uri(implode("|",array_keys($oc))."|username|".($k!==null?"db|":"").session_name()),$B);return"$B[1]?".(sid()?SID."&":"").($Xi!="server"||$M!=""?urlencode($Xi)."=".urlencode($M)."&":"")."username=".urlencode($V).($k!=""?"&db=".urlencode($k):"").($B[2]?"&$B[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($A,$Oe=null){if($Oe!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($A!==null?$A:$_SERVER["REQUEST_URI"]))][]=$Oe;}if($A!==null){if($A=="")$A=".";header("Location: $A");exit;}}function
query_redirect($G,$A,$Oe,$_g=true,$Oc=true,$Xc=false,$ei=""){global$g,$m,$b;if($Oc){$Eh=microtime(true);$Xc=!$g->query($G);$ei=format_time($Eh);}$zh="";if($G)$zh=$b->messageQuery($G,$ei,$Xc);if($Xc){$m=error().$zh.script("messagesPrint();");return
false;}if($_g)redirect($A,$Oe.$zh);return
true;}function
queries($G){global$g;static$vg=array();static$Eh;if(!$Eh)$Eh=microtime(true);if($G===null)return
array(implode("\n",$vg),format_time($Eh));$vg[]=(preg_match('~;$~',$G)?"DELIMITER ;;\n$G;\nDELIMITER ":$G).";";return$g->query($G);}function
apply_queries($G,$S,$Kc='table'){foreach($S
as$Q){if(!queries("$G ".$Kc($Q)))return
false;}return
true;}function
queries_redirect($A,$Oe,$_g){list($vg,$ei)=queries(null);return
query_redirect($vg,$A,$Oe,$_g,false,!$_g,$ei);}function
format_time($Eh){return
lang(1,max(0,microtime(true)-$Eh));}function
relative_uri(){return
str_replace(":","%3a",preg_replace('~^[^?]*/([^?]*)~','\1',$_SERVER["REQUEST_URI"]));}function
remove_from_uri($Qf=""){return
substr(preg_replace("~(?<=[?&])($Qf".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
pagination($E,$Rb){return" ".($E==$Rb?$E+1:'<a href="'.h(remove_from_uri("page").($E?"&page=$E".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($E+1)."</a>");}function
get_file($y,$ac=false){$cd=$_FILES[$y];if(!$cd)return
null;foreach($cd
as$y=>$X)$cd[$y]=(array)$X;$I='';foreach($cd["error"]as$y=>$m){if($m)return$m;$C=$cd["name"][$y];$mi=$cd["tmp_name"][$y];$Fb=file_get_contents($ac&&preg_match('~\.gz$~',$C)?"compress.zlib://$mi":$mi);if($ac){$Eh=substr($Fb,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$Eh,$Fg))$Fb=iconv("utf-16","utf-8",$Fb);elseif($Eh=="\xEF\xBB\xBF")$Fb=substr($Fb,3);$I.=$Fb."\n\n";}else$I.=$Fb;}return$I;}function
upload_error($m){$Ke=($m==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($m?lang(2).($Ke?" ".lang(3,$Ke):""):lang(4));}function
repeat_pattern($ag,$xe){return
str_repeat("$ag{0,65535}",$xe/65535)."$ag{0,".($xe%65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$X));}function
shorten_utf8($P,$xe=80,$Kh=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$xe).")($)?)u",$P,$B))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$xe).")($)?)",$P,$B);return
h($B[1]).$Kh.(isset($B[2])?"":"<i>…</i>");}function
format_number($X){return
strtr(number_format($X,0,".",lang(5)),preg_split('~~u',lang(6),-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($X){return
preg_replace('~[^a-z0-9_]~i','-',$X);}function
hidden_fields($rg,$Md=array(),$jg=''){$I=false;foreach($rg
as$y=>$X){if(!in_array($y,$Md)){if(is_array($X))hidden_fields($X,array(),$y);else{$I=true;echo'<input type="hidden" name="'.h($jg?$jg."[$y]":$y).'" value="'.h($X).'">';}}}return$I;}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
table_status1($Q,$Yc=false){$I=table_status($Q,$Yc);return($I?$I:array("Name"=>$Q));}function
column_foreign_keys($Q){global$b;$I=array();foreach($b->foreignKeys($Q)as$q){foreach($q["source"]as$X)$I[$X][]=$q;}return$I;}function
enum_input($T,$Ia,$n,$Y,$Cc=null){global$b,$x;preg_match_all("~'((?:[^']|'')*)'~",$n["length"],$Fe);$I=($Cc!==null?"<label><input type='$T'$Ia value='$Cc'".((is_array($Y)?in_array($Cc,$Y):$Y===0)?" checked":"")."><i>".lang(7)."</i></label>":"");foreach($Fe[1]as$t=>$X){$X=stripcslashes(str_replace("''","'",$X));$gb=(is_int($Y)?$Y==$t+1:(is_array($Y)?in_array($t+1,$Y):$Y===$X));$I.=" <label><input type='$T'$Ia value='".($x=="sql"?$t+1:h($X))."'".($gb?' checked':'').'>'.h($b->editVal($X,$n)).'</label>';}return$I;}function
input($n,$Y,$s){global$U,$Hh,$b,$x;$C=h(bracket_escape($n["field"]));echo"<td class='function'>";if(is_array($Y)&&!$s){$Ea=array($Y);if(version_compare(PHP_VERSION,5.4)>=0)$Ea[]=JSON_PRETTY_PRINT;$Y=call_user_func_array('json_encode',$Ea);$s="json";}$Jg=($x=="mssql"&&$n["auto_increment"]);if($Jg&&!$_POST["save"])$s=null;$sd=(isset($_GET["select"])||$Jg?array("orig"=>lang(8)):array())+$b->editFunctions($n);$kc=stripos($n["default"],"GENERATED ALWAYS AS ")===0?" disabled=''":"";$Ia=" name='fields[$C]'$kc";if($x=="pgsql"&&in_array($n["type"],(array)$Hh[lang(9)])){$Gc=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = ".$U[$n["type"]]." ORDER BY enumsortorder");if($Gc){$n["type"]="enum";$n["length"]="'".implode("','",array_map('addslashes',$Gc))."'";}}if($n["type"]=="enum")echo
h($sd[""])."<td>".$b->editInput($_GET["edit"],$n,$Ia,$Y);else{$Cd=(in_array($s,$sd)||isset($sd[$s]));echo(count($sd)>1?"<select name='function[$C]'$kc>".optionlist($sd,$s===null||$Cd?$s:"")."</select>".on_help("getTarget(event).value.replace(/^SQL\$/, '')",1).script("qsl('select').onchange = functionChange;",""):h(reset($sd))).'<td>';$Xd=$b->editInput($_GET["edit"],$n,$Ia,$Y);if($Xd!="")echo$Xd;elseif(preg_match('~bool~',$n["type"]))echo"<input type='hidden'$Ia value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?" checked='checked'":"")."$Ia value='1'>";elseif($n["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$n["length"],$Fe);foreach($Fe[1]as$t=>$X){$X=stripcslashes(str_replace("''","'",$X));$gb=(is_int($Y)?($Y>>$t)&1:in_array($X,explode(",",$Y),true));echo" <label><input type='checkbox' name='fields[$C][$t]' value='".(1<<$t)."'".($gb?' checked':'').">".h($b->editVal($X,$n)).'</label>';}}elseif(preg_match('~blob|bytea|raw|file~',$n["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$C'>";elseif(($bi=preg_match('~text|lob|memo~i',$n["type"]))||preg_match("~\n~",$Y)){if($bi&&$x!="sqlite")$Ia.=" cols='50' rows='12'";else{$K=min(12,substr_count($Y,"\n")+1);$Ia.=" cols='30' rows='$K'".($K==1?" style='height: 1.2em;'":"");}echo"<textarea$Ia>".h($Y).'</textarea>';}elseif($s=="json"||preg_match('~^jsonb?$~',$n["type"]))echo"<textarea$Ia cols='50' rows='12' class='jush-js'>".h($Y).'</textarea>';else{$Me=(!preg_match('~int~',$n["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$n["length"],$B)?((preg_match("~binary~",$n["type"])?2:1)*$B[1]+($B[3]?1:0)+($B[2]&&!$n["unsigned"]?1:0)):($U[$n["type"]]?$U[$n["type"]]+($n["unsigned"]?0:1):0));if($x=='sql'&&min_version(5.6)&&preg_match('~time~',$n["type"]))$Me+=7;echo"<input".((!$Cd||$s==="")&&preg_match('~(?<!o)int(?!er)~',$n["type"])&&!preg_match('~\[\]~',$n["full_type"])?" type='number'":"")." value='".h($Y)."'".($Me?" data-maxlength='$Me'":"").(preg_match('~char|binary~',$n["type"])&&$Me>20?" size='40'":"")."$Ia>";}echo$b->editHint($_GET["edit"],$n,$Y);$ed=0;foreach($sd
as$y=>$X){if($y===""||!$X)break;$ed++;}if($ed)echo
script("mixin(qsl('td'), {onchange: partial(skipOriginal, $ed), oninput: function () { this.onchange(); }});");}}function
process_input($n){global$b,$l;if(stripos($n["default"],"GENERATED ALWAYS AS ")===0)return
null;$u=bracket_escape($n["field"]);$s=$_POST["function"][$u];$Y=$_POST["fields"][$u];if($n["type"]=="enum"){if($Y==-1)return
false;if($Y=="")return"NULL";return+$Y;}if($n["auto_increment"]&&$Y=="")return
null;if($s=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?idf_escape($n["field"]):false);if($s=="NULL")return"NULL";if($n["type"]=="set")return
array_sum((array)$Y);if($s=="json"){$s="";$Y=json_decode($Y,true);if(!is_array($Y))return
false;return$Y;}if(preg_match('~blob|bytea|raw|file~',$n["type"])&&ini_bool("file_uploads")){$cd=get_file("fields-$u");if(!is_string($cd))return
false;return$l->quoteBinary($cd);}return$b->processInput($n,$Y,$s);}function
fields_from_edit(){global$l;$I=array();foreach((array)$_POST["field_keys"]as$y=>$X){if($X!=""){$X=bracket_escape($X);$_POST["function"][$X]=$_POST["field_funs"][$y];$_POST["fields"][$X]=$_POST["field_vals"][$y];}}foreach((array)$_POST["fields"]as$y=>$X){$C=bracket_escape($y,1);$I[$C]=array("field"=>$C,"privileges"=>array("insert"=>1,"update"=>1),"null"=>1,"auto_increment"=>($y==$l->primary),);}return$I;}function
search_tables(){global$b,$g;$_GET["where"][0]["val"]=$_POST["query"];$fh="<ul>\n";foreach(table_status('',true)as$Q=>$R){$C=$b->tableName($R);if(isset($R["Engine"])&&$C!=""&&(!$_POST["tables"]||in_array($Q,$_POST["tables"]))){$H=$g->query("SELECT".limit("1 FROM ".table($Q)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($Q),array())),1));if(!$H||$H->fetch_row()){$ng="<a href='".h(ME."select=".urlencode($Q)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$C</a>";echo"$fh<li>".($H?$ng:"<p class='error'>$ng: ".error())."\n";$fh="";}}}echo($fh?"<p class='message'>".lang(10):"</ul>")."\n";}function
dump_headers($Ld,$We=false){global$b;$I=$b->dumpHeaders($Ld,$We);$Mf=$_POST["output"];if($Mf!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($Ld).".$I".($Mf!="file"&&preg_match('~^[0-9a-z]+$~',$Mf)?".$Mf":""));session_write_close();ob_flush();flush();return$I;}function
dump_csv($J){foreach($J
as$y=>$X){if(preg_match('~["\n,;\t]|^0|\.\d*0$~',$X)||$X==="")$J[$y]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$J)."\r\n";}function
apply_sql_function($s,$d){return($s?($s=="unixepoch"?"DATETIME($d, '$s')":($s=="count distinct"?"COUNT(DISTINCT ":strtoupper("$s("))."$d)"):$d);}function
get_temp_dir(){$I=ini_get("upload_tmp_dir");if(!$I){if(function_exists('sys_get_temp_dir'))$I=sys_get_temp_dir();else{$p=@tempnam("","");if(!$p)return
false;$I=dirname($p);unlink($p);}}return$I;}function
file_open_lock($p){$r=@fopen($p,"r+");if(!$r){$r=@fopen($p,"w");if(!$r)return;chmod($p,0660);}flock($r,LOCK_EX);return$r;}function
file_write_unlock($r,$Tb){rewind($r);fwrite($r,$Tb);ftruncate($r,strlen($Tb));flock($r,LOCK_UN);fclose($r);}function
password_file($i){$p=get_temp_dir()."/adminer.key";$I=@file_get_contents($p);if($I||!$i)return$I;$r=@fopen($p,"w");if($r){chmod($p,0660);$I=rand_string();fwrite($r,$I);fclose($r);}return$I;}function
rand_string(){return
md5(uniqid(mt_rand(),true));}function
select_value($X,$_,$n,$di){global$b;if(is_array($X)){$I="";foreach($X
as$ie=>$W)$I.="<tr>".($X!=array_values($X)?"<th>".h($ie):"")."<td>".select_value($W,$_,$n,$di);return"<table>$I</table>";}if(!$_)$_=$b->selectLink($X,$n);if($_===null){if(is_mail($X))$_="mailto:$X";if(is_url($X))$_=$X;}$I=$b->editVal($X,$n);if($I!==null){if(!is_utf8($I))$I="\0";elseif($di!=""&&is_shortable($n))$I=shorten_utf8($I,max(0,+$di));else$I=h($I);}return$b->selectVal($I,$_,$n,$X);}function
is_mail($_c){$Ha='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$nc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$ag="$Ha+(\\.$Ha+)*@($nc?\\.)+$nc";return
is_string($_c)&&preg_match("(^$ag(,\\s*$ag)*\$)i",$_c);}function
is_url($P){$nc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^(https?)://($nc?\\.)+$nc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$P);}function
is_shortable($n){return
preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~',$n["type"]);}function
count_rows($Q,$Z,$de,$wd){global$x;$G=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ",$Z):"");return($de&&($x=="sql"||count($wd)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$wd).")$G":"SELECT COUNT(*)".($de?" FROM (SELECT 1$G GROUP BY ".implode(", ",$wd).") x":$G));}function
slow_query($G){global$b,$oi,$l;$k=$b->database();$fi=$b->queryTimeout();$th=$l->slowQuery($G,$fi);if(!$th&&support("kill")&&is_object($h=connect())&&($k==""||$h->select_db($k))){$le=$h->result(connection_id());echo'<script',nonce(),'>
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'kill=',$le,'&token=',$oi,'\');
}, ',1000*$fi,');
</script>
';}else$h=null;ob_flush();flush();$I=@get_key_vals(($th?$th:$G),$h,false);if($h){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$I;}function
get_token(){$yg=rand(1,1e6);return($yg^$_SESSION["token"]).":$yg";}function
verify_token(){list($oi,$yg)=explode(":",$_POST["token"]);return($yg^$_SESSION["token"])==$oi;}function
lzw_decompress($Ra){$jc=256;$Sa=8;$mb=array();$Lg=0;$Mg=0;for($t=0;$t<strlen($Ra);$t++){$Lg=($Lg<<8)+ord($Ra[$t]);$Mg+=8;if($Mg>=$Sa){$Mg-=$Sa;$mb[]=$Lg>>$Mg;$Lg&=(1<<$Mg)-1;$jc++;if($jc>>$Sa)$Sa++;}}$ic=range("\0","\xFF");$I="";foreach($mb
as$t=>$lb){$zc=$ic[$lb];if(!isset($zc))$zc=$mj.$mj[0];$I.=$zc;if($t)$ic[]=$mj.$zc[0];$mj=$zc;}return$I;}function
on_help($ub,$qh=0){return
script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $ub, $qh) }, onmouseout: helpMouseout});","");}function
edit_form($Q,$o,$J,$Ki){global$b,$x,$oi,$m;$Ph=$b->tableName(table_status1($Q,true));page_header(($Ki?lang(11):lang(12)),$m,array("select"=>array($Q,$Ph)),$Ph);$b->editRowPrint($Q,$o,$J,$Ki);if($J===false){echo"<p class='error'>".lang(13)."\n";return;}echo'<form action="" method="post" enctype="multipart/form-data" id="form">
';if(!$o)echo"<p class='error'>".lang(14)."\n";else{echo"<table class='layout'>".script("qsl('table').onkeydown = editingKeydown;");foreach($o
as$C=>$n){echo"<tr><th>".$b->fieldName($n);$bc=$_GET["set"][bracket_escape($C)];if($bc===null){$bc=$n["default"];if($n["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$bc,$Fg))$bc=$Fg[1];}$Y=($J!==null?($J[$C]!=""&&$x=="sql"&&preg_match("~enum|set~",$n["type"])?(is_array($J[$C])?array_sum($J[$C]):+$J[$C]):(is_bool($J[$C])?+$J[$C]:$J[$C])):(!$Ki&&$n["auto_increment"]?"":(isset($_GET["select"])?false:$bc)));if(!$_POST["save"]&&is_string($Y))$Y=$b->editVal($Y,$n);$s=($_POST["save"]?(string)$_POST["function"][$C]:($Ki&&preg_match('~^CURRENT_TIMESTAMP~i',$n["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));if(!$_POST&&!$Ki&&$Y==$n["default"]&&preg_match('~^[\w.]+\(~',$Y))$s="SQL";if(preg_match("~time~",$n["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$Y)){$Y="";$s="now";}if($n["type"]=="uuid"&&$Y=="uuid()"){$Y="";$s="uuid";}input($n,$Y,$s);echo"\n";}if(!support("table"))echo"<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]",$b->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($o){echo"<input type='submit' value='".lang(15)."'>\n";if(!isset($_GET["select"])){echo"<input type='submit' name='insert' value='".($Ki?lang(16):lang(17))."' title='Ctrl+Shift+Enter'>\n",($Ki?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".lang(18)."…', this); };"):"");}}echo($Ki?"<input type='submit' name='delete' value='".lang(19)."'>".confirm()."\n":($_POST||!$o?"":script("focus(qsa('td', qs('#form'))[1].firstChild);")));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$oi,'">
</form>
';}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
