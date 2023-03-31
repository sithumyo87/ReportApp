<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report App</title>
    <link href="{{ asset('css/jsPDF.css') }}" rel="stylesheet" type="text/css">
    
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

    <!-- html2canvas library -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>

    <!-- jsPDF library -->
    <script src="{{ asset('/parallax-jsPDF/dist/jspdf.umd.js') }}"></script>

</head>

<body>

<button onclick="Convert_HTML_To_PDF();">Convert HTML to PDF</button>

    <!-- HTML content for PDF creation -->
    <div id="contentToPrint">
        <h1>What is Lorem Ipsum?</h1>
        
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
        
        <img src="{{ asset('img/itp.png')}}">
        <img src="{{ asset('img/itp.png')}}">

    </div>


    <script>
            window.jsPDF = window.jspdf.jsPDF;

            // Convert HTML content to PDF
            function Convert_HTML_To_PDF() {
                var doc = new jsPDF();
                applyPlugin(window.jsPDF);

                $('#Btn_1').dxButton({
                    text: 'Export to PDF',
                    onClick: () => {
                    const pdfDoc = new jsPDF('p', 'pt', 'a4');
                    const options = {
                        pdfDoc: pdfDoc,
                        component: $('#DataGrid').dxDataGrid('instance')
                    };

                    exportDataGrid(options).then(() => {
                        pdfDoc.setFontSize(12);
                        const pageCount = pdfDoc.internal.getNumberOfPages();
                        for(let i = 1; i <= pageCount; i++) {
                            pdfDoc.setPage(i);
                            const pageSize = pdfDoc.internal.pageSize;
                            const pageWidth = pageSize.width ? pageSize.width : pageSize.getWidth();
                            const pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();
                            const header = 'Report 2014';
                            const footer = `Page ${i} of ${pageCount}`;

                            // Header
                            pdfDoc.text(header, 40, 15, { baseline: 'top' });

                            // Footer
                            pdfDoc.text(footer, pageWidth / 2 - (pdfDoc.getTextWidth(footer) / 2), pageHeight - 15, { baseline: 'bottom' });
                        }
                    }).then(() => {
                        pdfDoc.save('filePDF.pdf');
                    });
                    }
                });
                
                // Source HTMLElement or a string containing HTML.
                var elementHTML = document.querySelector("#contentToPrint");

                doc.html(elementHTML, {
                    callback: function(doc) {
                        // Save the PDF
                        doc.save('document-html.pdf');
                    },
                    margin: [10, 10, 10, 10],
                    autoPaging: 'text',
                    x: 0,
                    y: 0,
                    width: 190, //target width in the PDF document
                    windowWidth: 675 //window width in CSS pixels
                });
            }
    </script>
    
</body>

</html>