<html>
    <head>
        <script type="text/javascript" src="/js/jquery/jquery.js"></script>
    </head>
    <body>
        <div id="test">getstatus.php response time : <span id="resultTime">Wait...</span></div>
        <div id="test">javascript process time : <span id="jsTime">Wait...</span></div>
        <script type="text/javascript">
            /*$(document).ready(function(){
                setInterval(function(){
                    $.ajax({
                        url: '/getstatus.php',
                        success: function(data){
                            $('#resultTime').text(data);
                        }
                    });
                }, 1000);
            });*/

            var xmlhttp = null;
            if(window.XMLHttpRequest){
                // code for all new browsers
                xmlhttp = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                // code for IE5 and IE6
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            setInterval(function(){
                var start = new Date();
                var startTime = start.getTime();

                /* Set up the request */
                xmlhttp.open('GET', '/getstatus.php?test=true', true);

                /* The callback function */
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4){
                        if (xmlhttp.status == 200){
                            document.getElementById('resultTime').innerHTML = xmlhttp.responseText;


                            var end = new Date();
                            var endTime = end.getTime() - start;

                            document.getElementById('jsTime').innerHTML = endTime + 'ms';
                        }
                    }
                }

                /* Send the POST request */
                xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xmlhttp.send('auction_1=1&auction_2=2&auction_3=3&auction_4=4&auction_5=5&auction_6=6&auction_7=7');
            }, 1000);
        </script>
    </body>
</html>
