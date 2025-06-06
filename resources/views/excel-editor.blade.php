<!DOCTYPE html>
<html>
<head>
  <title>Excel Editor</title>
  <script src="https://cdn.jsdelivr.net/npm/handsontable@14.0.0/dist/handsontable.full.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.0.0/dist/handsontable.full.min.css">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <div id="hot"></div>

  <script>
    let hot;

    hot = new Handsontable(document.getElementById('hot'), {
          data: @json($data),
          rowHeaders: true,
          colHeaders: true,
          licenseKey: 'non-commercial-and-evaluation'
        });
  </script>
</body>
</html>
