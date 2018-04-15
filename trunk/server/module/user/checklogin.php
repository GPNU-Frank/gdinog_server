<?php
/*
 * 检测是否登陆
 */
if (GetCookie('uid')) {
	OutPut ( true, 10010);
} else {
	OutPut ( false,10011);
}
?>