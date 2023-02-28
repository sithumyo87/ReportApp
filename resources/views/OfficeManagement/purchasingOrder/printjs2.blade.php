<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Report App</title>

    <link href="{{ asset('printjs/print.min.css') }}" rel="stylesheet">
    


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

<!-- start detail -->
<div class="table-wrap" id="print" style="page-break-inside: avoid;">
    <table class="table table-bordered table-break">
        <thead>
            <tr>
                <th width="20">Lot No</th>
                <th>Description</th>
                <th>Unit in Price </th>
                <th>QTY</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @for ($i=1; $i<=20; $i++)
            <tr>
                <td width="10"  class="text-center">1</td>
                <td width="40%" width="40%" >
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p>&nbsp;</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                    <p>&nbsp;</p>
                    <p><strong>Model DELL OptiPlex 7070 Mini Tower</strong><br /><strong>Processor&nbsp;</strong>Intel&reg; Core i7-9700 (8 Cores/12MB/8T/3.0GHz to 4.8GHz/65W)<br /><strong>Memory&nbsp;</strong>8GB 1X8GB DDR4 2666MHz UDIMM Non-ECC<br /><strong>Hard Drive</strong>&nbsp;3.5" 1TB 7200rpm SATA Hard Disk Drive<br /><strong>Graphics&nbsp;</strong>AMD Radeon R5 430, 2GB, FH (DP/DP)<br /><strong>Optical Drive</strong>&nbsp;8x DVD+/-RW 9.5mm Optical Disk Drive<br /><strong>Network&nbsp;</strong>Integrated Gigabit Ethernet<br /><strong>Monitor&nbsp;</strong>No Monitor<br /><strong>Keyboard&nbsp;</strong>Dell Wired Keyboard KB216 Black (English)<br /><strong>Mouse&nbsp;</strong>Dell Optical Mouse - MS116 - Black<br /><strong>O/S</strong>&nbsp;Windows 10 Pro (64bit)/Windows 10 Pro A05 OS Recovery 64bit-DVD APJ</p>
                </td>
                <td width="20%" class="text-right">300.00 THB</td>
                <td width="10%" class="text-right">2</td>
                <td width="20%" class="text-right">315.00 THB</td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>
<!-- end detail -->
    
</body>

</html>