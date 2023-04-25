<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Print</title>
    <link href="{{ asset('css/kinzi_print.css') }}" rel="stylesheet" type="text/css">
</head>

<body>

    <div class="container-fluid">
        <div class="btn-block">
            {{-- <button onclick="Convert_HTML_To_PDF();">Save As PDF</button> --}}
            <button class="btn btn-danger btn-print" onclick="test();">Print It!</button>
            <button class="btn btn-danger btn-print" onclick="close_it();">Close It!</button>
        </div>

        <div id="contentToPrint">
            <div class="header">
                <div class="header-div">
                    <div class="left-div">
                        <img src="{{ asset('img/nexthop-logo.png') }}" width="50px">
                        <img src="{{ asset('img/itp.png') }}" width="140px" class="p-l-10">
                    </div>
                    <div class="right-div text-right">
                        <div>Next Hop IT Service Provider</div>
                        <div>Route To Future</div>
                        <div>For Your Business</div>
                    </div>
                </div>
            </div>

            <div class="example">

                @yield('content')
            </div>


            <div class=" report-footer">
                <footer class=" report-footer">
                    <div class="pagenum" style="float: right"></div>
                    <div>Building 371, Room 302, Yar Zar Dirit Housing, Botataung Township, Yangon, Myanmar</div>
                    <div>Tel: 09-5012101, 09-73129351, 09-5033257, Email : info@thenexthop.net , Website :
                        <a href="https://www.thenexthop.net/" class="footer-link">www.thenexthop.net</a>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- html2canvas library -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

    <!-- jsPDF library -->
    <script src="{{ asset('/parallax-jsPDF/dist/jspdf.umd.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/kinzi.print.min.js') }}"></script>
    <script>

        window.jsPDF = window.jspdf.jsPDF;

            // Convert HTML content to PDF
            function Convert_HTML_To_PDF() {
                var doc = new jsPDF('p', 'pt', 'a4');
                
                // Source HTMLElement or a string containing HTML.
                var elementHTML = document.querySelector("#contentToPrint");

                doc.html(elementHTML, {
                    callback: function(doc) {
                        // Save the PDF
                        doc.save('document-html.pdf');
                    },
                    margin: [0, 0, 0, 0],
                    autoPaging: 'text',
                    x: 0,
                    y: 0,
                    width: 190, //target width in the PDF document
                    windowWidth: 675 //window width in CSS pixels
                });
            }

        function test(){
            $('.example').kinziPrint({
                importCSS: false,
                loadCSS: "{{ asset('css/kinzi_print.css') }}",
                debug: false,
                header: $('.header').html(),
                footer: $('.report-footer').html(),
                printDelay: 0,
            });
        }

        function close_it(){
            window.close();
        }

        $( window ).on("load", function() {
           Convert_HTML_To_PDF();
        });

        window.onafterprint = function(e){
            $(window).off('mousemove', window.onafterprint);
            close_it();
        };

    </script>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-36251023-1']);
        _gaq.push(['_setDomainName', 'jqueryscript.net']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') +
                '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>
    <script>
        try {
            fetch(new Request("https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js", {
                method: 'HEAD',
                mode: 'no-cors'
            })).then(function(response) {
                return true;
            }).catch(function(e) {
                var carbonScript = document.createElement("script");
                carbonScript.src = "//cdn.carbonads.com/carbon.js?serve=CK7DKKQU&placement=wwwjqueryscriptnet";
                carbonScript.id = "_carbonads_js";
                document.getElementById("carbon-block").appendChild(carbonScript);
            });
        } catch (error) {
            console.log(error);
        }
    </script>
    
</body>

</html>
