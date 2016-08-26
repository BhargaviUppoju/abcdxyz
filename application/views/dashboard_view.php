<?php
$role = $this->customsession->getData(USER_ROLE);
$uname = $this->customsession->getData(USERNAME);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Dashboard Page</title>
    </head>
    <body>
        
        <?php if ($role == USER_TRAINEE) { ?>
            <p>Hello <?php echo $uname; ?></p>
            <pre>These are the list of trainers available 
            <?php foreach ($trainersList as $key => $data) { ?>
                       * <a target="_blank" href="<?php// echo commonHelperGetPageUrl('teacher'); ?>"><?php echo $data['username']; ?></a>
            <?php } ?>
            </pre>
        <?php } else { ?> 
            <p>Hello <?php echo $uname; ?></p>
        <?php } ?>
            <div id="logout">
            <button><a href="<?php echo commonHelperGetPageUrl('logout')?>">Logout</a></button>
        </div>
    </body>
</html>


