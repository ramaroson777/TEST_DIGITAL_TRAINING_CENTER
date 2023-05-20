<?php
// 0330225628
// Récupérer les données API jsonplaceholder.typicode.com/comments
$apiUrl = 'https://jsonplaceholder.typicode.com/comments';

// $apiUrl = 'comments.json';
$response = file_get_contents($apiUrl);

// Vérifier file_get_contents
if ($response === false) {
    die('Erreur lors de la récupération des données de l\'API.');
}

$comments = json_decode($response, true);

if( isset($_POST['allcomment']) ){
   
    foreach ($comments as $comment) {
        echo "<tr>
            <th scope='row'>" . $comment['postId'] . "</th>
            <td>" . $comment['id'] . "</td>
            <td>" . $comment['name'] . "</td>
            <td>" . $comment['email'] . "</td>
            <td>" . $comment['body'] . "</td>
            </tr>";
    }
    exit();
}

if( isset($_POST['filtreByPostId']) ){
    // Filtrer les commentaires avec postId 
    $filteredComments = array_filter($comments, function ($comment) {
        return $comment['postId'] == $_POST['val_postid'];
    });
    foreach ($filteredComments as $comment) {
        echo "<tr>
            <th scope='row'>" . $comment['postId'] . "</th>
            <td>" . $comment['id'] . "</td>
            <td>" . $comment['name'] . "</td>
            <td>" . $comment['email'] . "</td>
            <td>" . $comment['body'] . "</td>
            </tr>";
    }
    exit();
}

if( isset($_POST['filtreByPostIdId']) ){
    // Filtrer les commentaires avec postId=1 et id
    $filteredComments = array_filter($comments, function ($comment) {
        return $comment['postId'] == $_POST['postid'] && $comment['id'] == $_POST['id'];
    });

    // Vérifier s'il y a des commentaires correspondants
    if (!empty($filteredComments)) {
        foreach ($filteredComments as $comment) {
            echo "<tr>
                <th scope='row'>" . $comment['postId'] . "</th>
                <td>" . $comment['id'] . "</td>
                <td>" . $comment['name'] . "</td>
                <td>" . $comment['email'] . "</td>
                <td>" . $comment['body'] . "</td>
                </tr>";
        }
    }
    exit();
}

if( isset($_POST['idDisponible']) ){
    // Tableau pour stocker les ID disponibles
    $availableIds = [];

    foreach ($comments as $comment) {
        if ($comment['postId'] == $_POST['postid'] ) {
            $availableIds[] = $comment['id'];
        }
    }

    $resIdDisponible="";
    $resIdDisponible.="<select id='id'>";
    foreach ($availableIds as $key => $value) {
        $resIdDisponible.="<option>$value</option>";
    }
    $resIdDisponible.="</select>";

    echo $resIdDisponible;
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="loader" style="display:none;">
        <img src="assets/img/loader.gif" alt="Loading..." style="width: 80px;"/>
    </div>
    
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" aria-current="page" href="#" onclick="allcomment()">Tous lister</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" onclick="commentByPostId()">Trier par postId</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" onclick="commentByPostIdId()">Trier par PostID et Id</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>

        <div class="col-lg-12" id="allcomment" style="display:none;">
            <p class="text-center mt-2">Liste de tous les commentaires</p>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">postId</th>
                  <th scope="col">id</th>
                  <th scope="col">name</th>
                  <th scope="col">email</th>
                  <th scope="col">body</th>
                </tr>
              </thead>
              <tbody id="resAllComment" style="display:none;">
                
              </tbody>
            </table>
        </div>

        <div class="col-lg-12" id="commentByPostId" style="display:none;">
            <p class="text-center mt-2">Recherche commentaire par postId</p>
            <p>
                POST ID : <input type="text" id="val_postid"><input type="button" value="Rechercher" onclick="filtreByPostId()">
            </p>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">postId</th>
                  <th scope="col">id</th>
                  <th scope="col">name</th>
                  <th scope="col">email</th>
                  <th scope="col">body</th>
                </tr>
              </thead>
              <tbody id="resFiltreCommentByPostId" style="display:none;">
                 
              </tbody>
            </table>
        </div>

        <div class="col-lg-12" id="commentByPostIdId" style="display:none;">
            <p class="text-center mt-2">Recherche commentaire par postId + Id</p>
            <p>
                POST ID : <input type="text" id="postid" onchange="idDisponible()">
                ID : <span id="resIdDisponible"> </span> 
                <input type="button" value="Rechercher" onclick="filtreByPostIdId()">
            </p>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">postId</th>
                  <th scope="col">id</th>
                  <th scope="col">name</th>
                  <th scope="col">email</th>
                  <th scope="col">body</th>
                </tr>
              </thead>
              <tbody id="resFiltreCommentByPostIdId" style="display:none;">
                 
              </tbody>
            </table>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="assets/js/jquery.min.js" ></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) 
        {
            allcomment(); 
        });  

        function commentByPostId(){
            $("#allcomment").hide();
            $("#commentByPostId").show();
            $("#commentByPostIdId").hide();
            $("#val_postid").val('');
        } 

        function commentByPostIdId(){
            $("#allcomment").hide();
            $("#commentByPostId").hide();
            $("#commentByPostIdId").show();
            $("#postid").val('');
            $("#resIdDisponible").hide();
        } 

        // filtre commentaire par postId filtreByPostIdId
        function filtreByPostId(){
            $(".loader").show();
            console.log('filtreByPostId');
            console.log($("#val_postid").val());
            $("#resFiltreCommentByPostId").hide();

            $.ajax({
                url: "index.php",
                async: true,
                cache: false,
                type: "POST",
                data: "filtreByPostId=1&val_postid="+$("#val_postid").val(),
                error : function( XMLHttpRequest, textStatus, errorThrown ){ alert( "Impossible de charger l'explorateur" ); },
                success: function( responseText ){
                    $("#resFiltreCommentByPostId").html(responseText);  
                    $("#resFiltreCommentByPostId").show();
                    $(".loader").hide();
                }
            });
           
        }  

        // filtre commentaire par postId et id
        function filtreByPostIdId(){
            $(".loader").show();       
            $("#resFiltreCommentByPostIdId").hide();
            if( $("#id").val()===undefined || $("#id").val()===null ){
                alert('Selectionnez un ID !');
            }else{
                $.ajax({
                    url: "index.php",
                    async: true,
                    cache: false,
                    type: "POST",
                    data: "filtreByPostIdId=1&postid="+$("#postid").val()+"&id="+$("#id").val(),
                    error : function( XMLHttpRequest, textStatus, errorThrown ){ alert( "Impossible de charger l'explorateur" ); },
                    success: function( responseText ){
                        $("#resFiltreCommentByPostIdId").html(responseText);  
                        $("#resFiltreCommentByPostIdId").show();
                        $(".loader").hide();
                    }
                });
            }
            
        }  

        function allcomment(){
            $(".loader").show();
            $("#allcomment").show();
            $("#commentByPostId").hide();
            $("#commentByPostIdId").hide();
            $("#resAllComment").hide();
            $.ajax({
                url: "index.php",
                async: true,
                cache: false,
                type: "POST",
                data: "allcomment=1",
                error : function( XMLHttpRequest, textStatus, errorThrown ){ alert( "Impossible de charger l'explorateur" ); },
                success: function( responseText ){
                    $("#resAllComment").html(responseText);  
                    $("#resAllComment").show();
                    $(".loader").hide();
                }
            });
        }

        function idDisponible(){
            $(".loader").show();
            $("#resIdDisponible").hide();    
            $.ajax({
                url: "index.php",
                async: true,
                cache: false,
                type: "POST",
                data: "idDisponible=1&postid="+$("#postid").val(),
                error : function( XMLHttpRequest, textStatus, errorThrown ){ alert( "Impossible de charger l'explorateur" ); },
                success: function( responseText ){
                    $("#resIdDisponible").html(responseText);
                    $("#resIdDisponible").show();    
                    $(".loader").hide();
                }
            });
        }

    </script>
</body>
</html>
