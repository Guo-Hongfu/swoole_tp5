echo "loading..."
// 通过进程别名拿到进程pid
pid=`pidof live_master`
echo $pid
//平滑重启主进程pid
kill -USR1 $pid
echo "loading success"