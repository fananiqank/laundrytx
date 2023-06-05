
            <?php
            
                // $main_my=$con2->select("(select m.* FROM mos_menu AS m 
                //                      left join mos_graccess_groupmenu as gra on gra.menuid=m.id or gra.menuid=0 
                //                      left join mos_graccess_usergroup as usr on gra.groupid=usr.groupid ",
                //                      "*","menutype='mainmenu' AND published='1' AND access <= '2' AND (usr.userid=710 or usr.userid=0) and parent =0 ORDER BY parent,ordering)a");
                // SELECT m.* FROM mos_menu AS m left join mos_graccess_groupmenu as gra on gra.menuid=m.id or gra.menuid=0 left join mos_graccess_usergroup as usr on gra.groupid=usr.groupid WHERE menutype='mainmenu' AND published='1' AND access <= '2' AND (usr.userid=710 or usr.userid=0 ) ORDER BY parent,ordering
                $main_my=$con2->select( "mos_menu AS m 
                                         join mos_graccess_groupmenu as gra on gra.menuid=m.id
                                        ",
                                        "m.*",
                                        "menutype='mainmenu' AND published='1' AND access <= '2' AND gra.groupid=".$_SESSION ['ID_ROLE'] ." and parent=0 ORDER BY parent,ordering");

                ?>
                
                                        
                <?php
                foreach($main_my as $my){
                   $ceklink = "content.php?option=".$_GET['option']."&task=".$_GET['task']."&act=".$_GET['act'];
                   foreach($con2->select("mos_menu","parent"," link = '".$ceklink."'") as $getugr){}
                
                    if($my['id']==$getugr['parent']){
                        $expanded= "nav-expanded";
                    } else {
                        $expanded = "";
                    }

                    echo "<li class=\"nav-parent ".$expanded."\">";
                    echo "<a class=\"nav-link\" href=\"javascript:void(0)\">
                                <i class=\"fa fa-bars\" aria-hidden=\"true\"></i>
                                <span>$my[name]</span>
                          </a>";
                    $my2=$con2->select("mos_menu AS m 
                                        join mos_graccess_groupmenu as gra on gra.menuid=m.id",
                                       "m.*",
                                       "menutype='mainmenu' AND published='1' AND access <= '2' AND gra.groupid=".$_SESSION ['ID_ROLE'] ." and parent='$my[id]' ORDER BY parent,ordering");
                    // foreach()
                   
                   echo"<ul class=\"nav nav-children\">";
                   if(count($my2)>0){
                        foreach($my2 as $myv){
                            $explink2 = explode("&",$myv['link']);
                            $exptask2 = explode("=",$explink2[1]);
                            $gettask2 = $exptask2[1];

                            if($_GET['task']==$gettask2){
                                $active= "class='nav-active'";
                            } else {
                                $active = "";
                            }

                            $my3=$con2->select("mos_menu AS m 
                                                join mos_graccess_groupmenu as gra on gra.menuid=m.id",
                                                "m.*",
                                                "menutype='mainmenu' AND published='1' AND access <= '2' AND gra.groupid=".$_SESSION ['ID_ROLE'] ." and parent='$myv[id]' ORDER BY parent,ordering");
                        if(count($my3)<1){
                            
                            echo "  <li ".$active.">
                                        <a href=\"$myv[link]\">
                                            $myv[name]
                                        </a>
                                    </li>";
                        } else {
                            echo"   
                                    <li class=\"nav-parent\">
                                        <a class=\"nav-link\" href=\"#\">
                                            $myv[name]
                                        </a>
                                        <ul class=\"nav nav-children\">";
                            
                            foreach($my3 as $myv2){
                            $my4=$con2->select("mos_menu AS m 
                                                join mos_graccess_groupmenu as gra on gra.menuid=m.id",
                                                "m.*",
                                                "menutype='mainmenu' AND published='1' AND access <= '2' AND gra.groupid=".$_SESSION ['ID_ROLE'] ." and parent='$myv2[id]' ORDER BY parent,ordering");   
                            if(count($my4)<1){
                            echo"           <li>
                                                <a href=\"$myv2[link]\">
                                                   $myv2[name]
                                                </a>
                                            </li>";
                            } else {              
                            echo"           <li class=\"nav-parent\">
                                                <a class=\"nav-link\" href=\"#\">
                                                    $myv2[name]
                                                </a>
                                                <ul class=\"nav nav-children\">";
                            foreach($my4 as $myv3){
                            echo"                   <li>
                                                        <a href=\"$myv3[link]\">
                                                            $myv3[name]
                                                        </a>
                                                    </li>
                                                    ";
                            }
                            echo"                </ul>
                                            </li>";
                            }
                            }
                            echo"       </ul>
                                    </li>";
                        }
                        
                            
                            
                        }
                   
                   }
                    echo "</ul>";
                ?>
                    

                    <?php
                    echo "<li>";
                }
            ?>
                    
                            