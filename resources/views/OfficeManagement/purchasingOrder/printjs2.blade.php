<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report App</title>

    <link href="{{ asset('printjs/print.min.css') }}" rel="stylesheet">

    <style>

    .rTable{
        display: table;
        width: 100%;
    }

    .rHead{
        display: table-header-group;
    }

    .rBody{
        display: table-row-group;
    }

    .rRow{
        display: table-row;
    }

    .rCell{
        display: table-cell;
        border: 1px solid #333;
        border-right: 0px;
    }

    .rCell:last-child{
        border-right: 1px solid #333;
    }


    .rTable {
   display: table;
   width: 100%;
}
.rTable {
   display: block;
   width: 100%;
}
.rTable:after {
   visibility: hidden;
   display: block;
   font-size: 0;
   content: " ";
   clear: both;
   height: 0;
}
.rTableHeading, .rTableBody, .rTableFoot, .rTableRow{
   clear: both;
}
.rTableHeading {
   display: table-header-group;
   background-color: #ddd;
}
.rTableCell, .rTableHead {
   border: 1px solid #999999;
   float: left;
   height: 17px;
   overflow: hidden;
   padding: 3px 1.8%;
   width: 28%;
}
.rTableHeading {
   display: table-header-group;
   background-color: #ddd;
   font-weight: bold;
}
.rTableFoot {
   display: table-footer-group;
   font-weight: bold;
   background-color: #ddd;
}
.rTableBody {
   display: table-row-group;
}

    </style>
    


    <script src="{{ asset('printjs/print.min.js') }}" rel="stylesheet"></script>

    <script>
        function printForm() {
            printJS({
                printable: 'print',
                type: 'html',
                targetStyles: ['*'],
                header: 'PrintJS - Print Form With Customized Header'
            });
        }
    </script>
</head>

<body>

<button type="button" onClick=printForm()>Print Form</button>

{{-- <h2>Phone numbers</h2>
    <div class="rTable">
        <div class="rTableRow">
            <div class="rTableHead"><strong>Name</strong></div>
            <div class="rTableHead"><span style="font-weight: bold;">Telephone</span></div>
            <div class="rTableHead">&nbsp;</div>
        </div>
    <div class="rTableRow">
    <div class="rTableCell">John</div>
    <div class="rTableCell"><a href="tel:0123456785">0123 456 785</a></div>
    <div class="rTableCell"><img src="images/check.gif" alt="checked" /></div>
    </div>
    <div class="rTableRow">
    <div class="rTableCell">Cassie</div>
    <div class="rTableCell"><a href="tel:9876532432">9876 532 432</a></div>
    <div class="rTableCell"><img src="images/check.gif" alt="checked" /></div>
    </div>
</div> --}}

<!-- start detail -->
    <div class="rTable">
        <div class="rHead">
            <div class="rRow">
                <div class="rCell" width="20">Lot No</div>
                <div class="rCell">Description</div>
                <div class="rCell">Unit in Price </div>
                <div class="rCell">QTY</div>
                <div class="rCell">Subtotal</div>
            </div>
        </div>
        <div class="rBody">
            @for ($i=1; $i<=20; $i++)
            <div class="rRow">
                <div class="rCell">1</div>
                <div class="rCell">
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p>&nbsp;</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p>&nbsp;</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                </div>
                <div class="rCell">300.00 THB</div>
                <div class="rCell">2</div>
                <div class="rCell">315.00 THB</div>
            </div>
            @endfor
        </div>
    </div>
<!-- end detail -->
    
</body>

</html>