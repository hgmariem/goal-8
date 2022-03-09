<?php use \Carbon\Carbon; 
    $result = '';
    $today = date('Y-m-d');
    $config_goal_types = config('constants.goal_types');
    #print_r($config_goal_types);
        
    foreach ($sub_goals as $j => $i) {
                $result .= '<li class="goal-row-child sub-container" helper="original" id="goal-' . ++$j . '" data-containerid="goal-container-' . $j . '" data-level="' . $i->level . '" data-pid="goal-' . $i->parent_id . '" data-autosaveid="'.$i->auto_save_id.'">';
                
                $result .= Form::hidden('sub_id', $i->id, array('data-collapse' => $i->self_collapse));
                $result .= '<div id="top-input-' . $i->id . '" class="goal-top" data-id="goal-top-' . $j . '">';
                #$result .= '<span><b>Goal</b></span>';
                /*$result .= Html::link('javascript:void(0);', (isset($i->type->name)) ? e('<span class="goal-title">  ' . $i->type->name . '</span>') : e('<b>Habit</b>'), array(
                            'class' => 'type yellow',
                            'data-value' => (isset($i->type->name)) ? $i->type->name : 'Habit',
                            'data-id' => (isset($i->type_id)) ? $i->type_id : 1,
                ));
                */
                #$result.=$i->type->name;
                $handle_goal = (!is_admin() && $is_default)?"new-handle-goal":"handle-goal";
                $new_type = (!is_admin() && $is_default)?"new-type":"type";

                $result .='<a class="'.$handle_goal.'" id="handle-sub" href="javascript:void(0);"><i class="fa fa-arrows"></i></a>';
                $result .= '<a href="javascript:void(0);" class="'.$new_type.' yellow" data-value="'.((isset($config_goal_types[$i->type_id])) ? $config_goal_types[$i->type_id] : "Habit").'" data-id="'.((isset($i->type_id)) ? $i->type_id : 1).'">'.((isset($config_goal_types[$i->type_id])) ? "<span class='goal-title'>  " . $config_goal_types[$i->type_id] . "</span>" : "<span class='goal-title'>Habit</span>").'</a>';
                
                

                $result .= '<input type="hidden" name="sub_habit_type" value="' . ( (1 == $i->type_id && !empty($i->habit_types)) ? $i->habit_types->type . ';' . $i->habit_types->value : '1;7' ) . '" />';

                
                
                $result .= '<input type="hidden" name="auto_save_id" value="'.$i->auto_save_id.'">';
                
                $lobby_display = ($i->type_id == 0) ? 'display:none;' : '';
                $new_lobby = (!is_admin() && $is_default)?"new-lobby":"lobby";
                
                /*$result .= Html::link('javascript:void(0);', (!$i->is_show_lobby) ? '<span class="goal-title goal-title-sub-text"><i class="fa fa-eye-slash"></i></span>' : '<span class="goal-title goal-title-sub-text"><i class="fa fa-eye"></i></span>', array(
                            'class' => (!$i->is_show_lobby) ? 'lobby red' : 'lobby green',
                            'data-value' => (!$i->is_show_lobby) ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>',
                            'data-id' => (!$i->is_show_lobby) ? 0 : 1,
                            'style' => $lobby_display,
                ));*/
                
                $result .='<a href="javascript:void(0);" class="'.((!$i->is_show_lobby) ? 'red' : 'green').' '.$new_lobby.'" data-value="'.((!$i->is_show_lobby) ? 0 :1).'" data-id="'.((!$i->is_show_lobby) ? 0 : 1).'" style="'.$lobby_display.'">'.((!$i->is_show_lobby) ? '<span class="goal-title goal-title-sub-text"><i class="fa fa-eye-slash"></i></span>' : '<span class="goal-title goal-title-sub-text"><i class="fa fa-eye"></i></span>').'</a>';
                
                if ($i->level == 0) {
                    /*$result .= Html::link('javascript:void(0);', (!$i->is_active) ? '<span class="goal-title goal-title-sub-text">Inactive</span>' : '<span class="goal-title goal-title-sub-text">Active</span>', array(
                                'class' => (!$i->is_active) ? 'status red' : 'status yellow',
                                'data-value' => (!$i->is_active) ? 'Inactive' : 'Active',
                                'data-id' => (!$i->is_active) ? 0 : 1,
                    ));*/
                    
                    $lclass=(!$i->is_active) ? 'status red' : 'status yellow';
                    $result .='<a href="javascript:void(0);" class="'.$lclass.'" data-value="'.((!$i->is_active) ? "Inactive" : "Active").'" data-id="'.((!$i->is_active) ? 0 : 1).'">'.((!$i->is_active) ? '<span class="goal-title goal-title-sub-text">Inactive</span>' : '<span class="goal-title goal-title-sub-text">Active</span>').'</a>';
                }

                $schedule_display = ($i->type_id != 1 && $i->type_id != 2) ? 'display:none;' : '';
                $habit_setting = (!is_admin() && $is_default)?"new-habit-schedule":"habit-schedule";
                #var_dump($i->habit_types);
                
                $result .='<a href="javascript:void(0);" class="'.$habit_setting.' settings" data-backdrop="static" data-keyboard="false" data-goal-id="'.$i->auto_save_id.'" data-goal-type="'.$i->type_id.'" id="sub_habit_type" style="'.$schedule_display.'" data-habit-type="'.( (1 == $i->type_id && !empty($i->habit_types)) ? $i->habit_types->type . ';' . $i->habit_types->value : '1;7' ).'" data-scale="'.( (isset($i->habit_types->is_scale) && !empty($i->habit_types->is_scale)) ? $i->habit_types->is_scale:0).'" data-min = "'.( (isset($i->habit_types->minimum) && !empty($i->habit_types->minimum)) ? $i->habit_types->minimum :"").'" data-max="'.( (isset($i->habit_types->maximum) && !empty($i->habit_types->maximum)) ? $i->habit_types->maximum :"").'" data-is_apply="'.( (isset($i->habit_types->is_apply) && !empty($i->habit_types->is_apply)) ? $i->habit_types->is_apply :"").'"><i class="fa fa-cog"></i></a>';

                //$task_template_display = ($i->type_id != 2) ? 'display:none;' : '';

                //$result .='<a href="javascript:void(0);" class="task-template settings"  id="task-template" style="'.$task_template_display.'"><i class="fa fa-cog"></i></a>';

                $result .= '<input type="hidden" name="add_text_type" value="'.(!empty($i->habit_types) ? $i->habit_types->text  : '' ) .'" />';
                $display = ($i->type_id != 2) ? 'display:none;' : 'display:inline-block;';
                $display_habit = ($i->type_id != 1) ? 'display:none;' : 'display:inline-block;';
                $due_date = Carbon::parse($i->due_date)->format('j F, Y');
                $habit_start_date = Carbon::parse($i->habit_start_date)->format('j F, Y');//$this->convertDate($i->habit_start_date, 'j F, Y', 'Y-m-d');
                $class = '';
                $input_class = '';
                $reactive = '';
                $trophy = '';
                if ($i->type_id == 2) {

                    if ($i->percent < 100 && $i->due_date < $today) {
                        $class = 'goal-due red over-due date picker__input';
                        $input_class = 'overdue-input';
                    } else if ($i->percent == 100) {
                        $input_class = 'green-input';
                        $reactive='<a href="javascript:void(0);" class="like btnReactive" gid="'.$i->id.'"><i class="fa fa-thumbs-o-up"></i></a>';
                  
                        $input_class = 'green-input';
                        // $trophy = CHtml::link('', 'javascript:void(0);', array(
                        //     'class' => 'trophy btnTrophy',
                        //     'gid'   => $i->id,
                        // ));
                        $trophyClass = '';
                        if ($i->is_in_trophy == 1)
                            $trophyClass .= 'trophy-red';
                        $trophy = "<a gid='" . $i->id . "' class='btnTrophy trophy-normal trophy " . $trophyClass . "' id='trophy-" . $i->id . "'><i id='trophy-icon-" . $i->id . "' class='fa fa-trophy " . $trophyClass . "'></i></a>";
                    }
                }

                $d_date=(!empty($i->due_date) && $i->due_date!='0000-00-00') ? $due_date : date('j F, Y');
                $remove_goal = (!is_admin() && $is_default)?"new-remove-goal":"remove-goal";
                $new_goal = (!is_admin() && $is_default)?"new-add-goal":"new-goal";
                $result .= Form::text('sub_due_date', $d_date , array(
                            'class' => ($class) ? $class : 'date fieldset__input js__datepicker goal-due',
                            'style' => $display,
                            'id' => '',
                            'readonly' => ($class) ? 'readonly' : '',
                            'gid' => $i->id,
                ));

                //var_dump($i->habit_start_date);
                $hs_date=(!empty($i->habit_start_date) && $i->habit_start_date!='0000-00-00') ? $habit_start_date : date('j F, Y');
                //var_dump($hs_date);

                $result .= Form::text('sub_habit_start_date', $hs_date , array(
                            'class' => 'date fieldset__input js__datepicker habit-start-date',
                            'style' => $display_habit,
                            'id' => '',
                            'gid' => $i->id,
                ));

                $result .= $reactive;

                $result .= $trophy;

                $result .="<span class='float-right'>";

                $result .='<a href="javascript:void(0);" class="'.$remove_goal.' new-sub-goal btnDelete" data-autosaveid="'.$i->auto_save_id.'"><i class="fa fa-close"></i></a>';
                $result .='<a href="javascript:void(0);" class="'.$new_goal.' new-sub-goal"><i class="fa fa-plus"></i></a>';
                $result .="</span>";
                $result .= '</div>';

                //$result .= '<div class="sub-title-goal " data-pid="' . $i->id . '">' . $i->name . '</div>';
                $result .= Form::text('sub_name', $i->name, array(
                            'placeholder' => "Name of sub goal",
                            // 'style' => 'display:none',
                            'class' => $input_class . ' text newly-added',
                            'id' => 'input-' . $i->id,
                            'ondrop' => 'drop_inside(event)',
                            'ondragover' => 'allowDrop(event)',
                            'data-id' => 'goal-input-' . $j,
                ));

                $result .= '<div class="clearfix"></div>';
                
                /**/
                if(isset($i->children) && !empty($i->children)){
                    $sub_goals=$i->children;
                    if(count($sub_goals)){

                        $result.="<ul style=display:".(($i->self_collapse)?"none":"block").">";
                            $result .=View::make('goals.sub_goal',compact('sub_goals','is_default'))->render();
                        $result.="</ul>";
                    }
                }
                $result .= '</li>';
    }
    echo $result;
?>