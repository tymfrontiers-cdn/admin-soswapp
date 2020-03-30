<?php
namespace TymFrontiers;

require_once "../app.init.php";
require_once APP_BASE_INC;
require_once APP_ROOT . "/src/Helper.php";

\require_login(true);
\check_access("/settings", true, "project-admin");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr" manifest="<?php echo WHOST; ?>/site.webmanifest">
  <head>
    <meta charset="utf-8">
    <title>Settings | <?php echo PRJ_TITLE; ?></title>
    <?php include PRJ_INC_ICONSET; ?>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'>
    <meta name="author" content="<?php echo PRJ_AUTHOR; ?>">
    <meta name="creator" content="<?php echo PRJ_CREATOR; ?>">
    <meta name="publisher" content="<?php echo PRJ_PUBLISHER; ?>">
    <meta name="robots" content='nofollow'>
    <!-- Theming styles -->
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/font-awesome-soswapp/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/theme-soswapp/css/theme.min.css">
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/theme-soswapp/css/theme-<?php echo PRJ_THEME; ?>.min.css">
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/fancybox-soswapp/css/fancybox.min.css">
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/jcrop-soswapp/css/jcrop.min.css">
    <!-- optional plugin -->
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/plugin-soswapp/css/plugin.min.css">
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/dnav-soswapp/css/dnav.min.css">
    <link rel="stylesheet" href="<?php echo WHOST; ?>/7os/faderbox-soswapp/css/faderbox.min.css">
    <!-- Project styling -->
    <link rel="stylesheet" href="<?php echo \html_style("base.min.css"); ?>">
  </head>
  <body>
    <?php \setup_page("project-admin/settings", "project-admin", true, PRJ_HEADER_HEIGHT); ?>
    <?php include PRJ_INC_HEADER; ?>

    <section id="main-content">
      <form
      id="delete-form"
      method="post"
      action="/DeleteSetting.php"
      data-path="/admin/src"
      data-domain="<?php echo WHOST;?>"
      data-validate="false"
      onsubmit="sos.form.submit(this,checkDelete);return false;"
      >
      <input type="hidden" name="form" value="setting-delete-form">
      <input type="hidden" name="CSRF_token" value="<?php echo $session->createCSRFtoken("setting-delete-form");?>">
      <input type="hidden" name="id" value="">
    </form>
      <div class="view-space">
        <br class="c-f">
          <div class="grid-8-tablet center-tablet">
            <form
              id="query-form"
              class="block-ui color blue"
              method="post"
              action="/FetchSetting.php"
              data-path="/admin/src"
              data-domain="<?php echo WHOST;?>"
              data-validate="false"
              onsubmit="sos.form.submit(this,doFetch);return false;"
              >
              <input type="hidden" name="form" value="setting-query-form">
              <input type="hidden" name="CSRF_token" value="<?php echo $session->createCSRFtoken("setting-query-form");?>">

              <div class="grid-6-tablet">
                <label for="search"> <i class="fas fa-search"></i> Search</label>
                <input type="search" name="search" value="<?php echo !empty($_GET['search']) ? $_GET['search'] :''; ?>" id="search" placeholder="Keyword search">
              </div>
              <div class="grid-4-phone grid-2-tablet">
                <label for="page"> <i class="fas fa-file-alt"></i> Page</label>
                <input type="number" name="page" id="page" placeholder="1" value="1">
              </div>
              <div class="grid-4-phone grid-2-tablet">
                <label for="limit"> <i class="fas fa-sort-numeric-up"></i> Limit</label>
                <input type="number" name="limit" id="limit" placeholder="25" value="25">
              </div>
              <div class="grid-4-phone grid-2-tablet"> <br>
                <button type="submit" class="btn blue"> <i class="fas fa-search"></i></button>
              </div>
              <br class="c-f">
            </form>
            <p class="align-c">
              <b>Records:</b> <span id="records">00</span> |
              <b>Pages:</b> <span id="pages">00</span>
            </p>
          </div>

          <div class="sec-div padding -p10">
            <h2>Settings</h2>
            <table class="vertical color blue padding -pnone  ff-open-sans">
              <thead class="color-bg align-l">
                <tr>
                  <th>Name/ Title</th>
                  <th>Value</th>
                  <th>Domain</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="record-list"></tbody>
            </table>
            <div class="sec-div padding -p5">
              <div class="grid-3-phone grid-1-tablet push-right padding -pnone">
                <button onclick="$(this).page()" type="button" id="next-page" style="width:100%" data-page="0" class="btn blue no-shadow no-radius"> <i class="fas fa-angle-right fa-lg"></i></button>
              </div>
              <div class="grid-3-phone grid-1-tablet push-right padding -pnone">
                <button onclick="$(this).page()" type="button" id="previous-page" style="width:100%" data-page="0" class="btn blue no-shadow no-radius"> <i class="fas fa-angle-left fa-lg"></i></button>
              </div>
            </div>
            <br class="c-f">
          </div>


        <br class="c-f">
      </div>
    </section>
    <!-- <button type="button" onclick="sos.faderBox.url(location.origin + '/admin/setting/make', {callback : 'refreshList'}, {exitBtn: true});" class="sos-btn blue" id="floatn-plus"> <i class="fas fa-plus"></i></button> -->
    <?php include PRJ_INC_FOOTER; ?>
    <!-- Required scripts -->
    <script src="<?php echo WHOST; ?>/7os/jquery-soswapp/js/jquery.min.js">  </script>
    <script src="<?php echo WHOST; ?>/7os/js-generic-soswapp/js/js-generic.min.js">  </script>
    <script src="<?php echo WHOST; ?>/7os/fancybox-soswapp/js/fancybox.min.js">  </script>
    <script src="<?php echo WHOST; ?>/7os/jcrop-soswapp/js/jcrop.min.js">  </script>
    <script src="<?php echo WHOST; ?>/7os/theme-soswapp/js/theme.min.js"></script>
    <!-- optional plugins -->
    <script src="<?php echo WHOST; ?>/7os/plugin-soswapp/js/plugin.min.js"></script>
    <script src="<?php echo WHOST; ?>/7os/dnav-soswapp/js/dnav.min.js"></script>
    <script src="<?php echo WHOST; ?>/7os/faderbox-soswapp/js/faderbox.min.js"></script>
    <!-- project scripts -->
    <script src="<?php echo \html_script ("base.min.js"); ?>"></script>
    <script src="<?php echo WHOST . "/admin/assets/js/www.min.js"; ?>"></script>
    <script type="text/javascript">
      var param = $("#param").data();
      function refreshList(){  $('#query-form').submit(); }
      function doFetch(data){
        // console.log(data);
        if( data && data.status == "0.0" && data.settings.length > 0){
          $('#pages').text(data.pages);
          $('#records').text(data.records);
          $('#page').val(data.page);
          $('#limit').val(data.limit);
          if( data.has_next_page ) $('#next-page').data('page',data.next_page);
          if( data.has_previous_page ) $('#previous-page').data('page',data.previous_page);
          $('#record-list').listData( data.settings );
          removeAlert();
        }else{
          $('#record-list').html('');
        }
      };
      $.fn.listData = function(obj){
        var html = "";
        $.each(obj, function(i, el) {
          html += "<tr>";
            // html += ( "<td>" + el.id + "("+(el.active ? 'ACTIVE' : 'INACTIVE')+")</td>" );
            html += ("<td title='"+el.min_desc+"'>");
            html += (
              "<a class=\"inherit\" href=\"javascript:void(0)\" onclick=\"faderBox.url(location.origin + '/admin/setting/make',{id:"+el.id+",key:'"+el.name+"',domain:'"+el.domain+"',callback:'refreshList'},{exitBtn:true});\"> <i class=\"fas fa-edit\"></i> ("+el.name + ") "+el.title+") </a>"
            );
            html+= "</td>";
            html += ( "<td>" +el.value+ "</td>" );
            html += ( "<td>" +el.domain+ "</td>" );

            html += "<td>";
              html += ("<a class=\"red\" href=\"javascript:void(0)\" onclick=\"doDelete("+el.id+")\"> <i class=\"fas fa-trash\"></i> Delete </a>");

            html+= "</td>";
          html += "</tr>";
        });
        $(this).html(html);
      };
      function doDelete(id){
        if( confirm("Are you sure you want to delete this setting value?") ){
          $("#delete-form input[name=id]").val(id);
          $('#delete-form').submit();
        }
      }
      function checkDelete(data){
        if( data && data.status == "0.0"){
          setTimeout(function(){
            removeAlert();
            refreshList();
          },1800);
        }
      }
      $(document).ready(function() {
        refreshList();
      });

    </script>
  </body>
</html>
