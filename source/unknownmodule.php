<?php

include('header.php');
include('menu.php');

$alert = new bsAlert("Unknown Module",bsAlert::WARNING);

$content = new bsContainer(bsContainer::CONTAINER);
$content->addContent($alert->show());
echo $content->show();

include('footer.php');