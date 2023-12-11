

<!doctype html>

<html lang="ar" dir="rtl">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet" />
  <style>
      .order-td {
          border-left: none;
          border-top: none;
          border-right: none;
          font-size: 12pt;
          text-align: right;
      }
      #content {
          align-items: center;
          display: inline-flex;
      }
      #towlabel {

          display: inline;
      }
      .float-container {
          border: 3px solid #fff;
          padding: 20px;
      }

      .float-child {

          float: right;
          padding: 2px;

      }
      float-child2 {
          width: 60%;
          float: left;
          padding: 2px;

      }
      body {
          counter-increment: pageplus1 page;
          counter-reset: pageplus1 1;
          direction: rtl;
          font-family: Amiri ;
          border: 1px;
      }

      #header {
          position: fixed;
          top: -115px;
          width: 100%;
          height: 109px;

      }
      #footer {
          position: fixed;
          bottom: -25px;
          height: 20px;

          text-align: center;
      }

      #footer .page:after {

          content: counter(page);
      }
      #footer .pageplus1:after {

          content:  counter(pageplus1);
      }
      @page {
          size: 21cm 29.7cm ;
          margin: 30px 40px 30px 40px;
      }
      table {
          width: 96%;
          border-collapse: collapse;
          font-size: 12px;
      }
      tr {
          line-height: 12px;
      }
      th {
          text-align: center;
          border: 1pt solid  gray;
          font-size: 12px;
          height: 30px;
      }
      caption {
          font-family: DejaVu Sans, sans-serif ;

      }
      thead {

          font-family: DejaVu Sans, sans-serif;
      }

      td {
          text-align: right;
          border: 1pt solid  lightgray;
      }
      .page-break {
          page-break-after: always;
      }
      br[style] {
          display:none;
      }
      .page-break {
          page-break-after: always;
      }
      #mainlabel  {
          display:inline-block;border-style: dotted;border-top: none;border-right: none;
          border-left: none;padding-left: 4px;padding-right: 4px;text-align: center;
      }
      #mainlabel2  {
          display:inline-block; height: 20px;
      }
  </style>
</head>
<body style="border-style: solid;border-width: 2px;border-color: #bf800c;" >

<div >

  @yield('mainrep')


</div>
</body>
</html>

