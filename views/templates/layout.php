<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $this->Shortcuts->getSiteTitle(). " - ". $pageMeta['Title'];?></title>
        <?php if(isset($pageMeta['Description'])){echo '<meta name="description" content="'.$pageMeta['Description'].'">';} ?>
        <?php if(isset($pageMeta['Keywords'])){echo '<meta name="keywords" content="'.$pageMeta['Keywords'].'">';} ?>
        <?php if(isset($pageMeta['Index']) && $pageMeta['Index'] == 0){echo '<meta name="robots" content="noindex, follow" /><meta name="googlebot" content="noindex" />';} ?>
        <link rel="icon" type="image/png" href="<?php echo $this->Shortcuts->getUrlSite();?>images/favicon.png" />
        <link href="<?php echo $this->Shortcuts->getUrlSite().'styles/style.css';?>" rel="stylesheet" type="text/css" >
        <script src="<?php echo $this->Shortcuts->getUrlSite().'scripts/jquery-2.1.0.min.js';?>"></script>
    </head>
    <body>
        <div id='conteneur-header'>
            <div id='enteteHeader'><a href="<?php echo $this->Shortcuts->getUrlSite();?>"><img src="<?php echo $this->Shortcuts->getUrlSite();?>images/head.png"/></a></div>
            <div id='menu-principal'>
                <div id="conteneurMenu">
                    <?php include 'views/templates/viewMenu.php';?>
                </div>    
            </div>
        </div>
        <div id='divDeContenu'>
        <?php echo $theView; ?>
        </div> 
    </body>
</html>