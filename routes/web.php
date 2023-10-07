<?php

use App\Http\Controllers\SupportTeam\StudentRecordController;
use App\Models\Resource;
use Box\Spout\Common\Entity\Row;

Auth::routes();

//Route::get('/test', 'TestController@index')->name('test');
Route::get('/privacy-policy', 'HomeController@privacy_policy')->name('privacy_policy');
Route::get('/terms-of-use', 'HomeController@terms_of_use')->name('terms_of_use');



Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@dashboard')->name('home');
    // Route::get('/home', 'HomeController@dashboard')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::group(['prefix' => 'setup'], function()
    {
        Route::group(['middleware'=>['teamSA','setup_status']], function(){
            Route::get('marking-period','setupController@marking_period')->name('setup.marking-period');
            Route::get('calendar','setupController@calendar')->name('setup.calendar');
            Route::get('periods','setupController@periods')->name('setup.periods');
            Route::get('grade-levels','setupController@grade_levels')->name('setup.grade-levels');
            Route::get('classrooms','setupController@classrooms')->name('setup.classrooms');
            Route::get('ajax/getSemesterQuarters/{sem_id}','setupController@get_semester_quarters')->name('setup.get-semester-quarters');
            Route::get('ajax/marking-period/{mp_id}','setupController@getMarkingPeriod');
            Route::post('edit_marking_period','setupController@MarkingPeriodEdit')->name('setup.edit-marking-period');
            Route::post('marking-periods/new','setupController@addMarkingPeriod')->name('setup.marking-periods-new');
            Route::post('class-periods/new','setupController@addClassPeriod')->name('setup.class-periods');
            Route::post('class-periods/update/{period_id}','setupController@updateClassPeriod');
            Route::delete('class-periods/delete/{period_id}','setupController@deleteClassPeriod');
            Route::post('grade-levels/add','setupController@addGradeLevels')->name('setup.grade-levels-add');
            Route::post('grade-levels/update/{grade_id}','setupController@updateGradeLevels');
            Route::post('grade-levels/delete/{grade_id}','setupController@deleteGradeLevels');
            Route::post('classrooms/add','setupController@addClassrooms');
            Route::post('classrooms/update/{room_id}','setupController@updateClassrooms');
            Route::post('classrooms/delete/{room_id}','setupController@deleteClassrooms');
            Route::group(['prefix'=>'calendar'],function(){
                Route::post('save-academic-year','setupController@saveAcademicYear')->name('setup.calendar.save-academic-year');
                Route::post('create-event','setupController@createCalendarEvents')->name('setup.calendar.create-event');
            });
            Route::group(['prefix'=> 'schools'], function(){
                Route::get('create','setupController@create_school')->name('setup.schools.create');
                Route::get('/','setupController@school_info')->name('setup.schools');
                Route::get('preferences','setupController@school_preferences')->name('setup.schools.preferences');
                Route::post('new','setupController@school_store')->name('setup.schools.new');
                Route::delete('remove/{id}','setupController@remove_school')->name('setup.schools.remove');
                Route::post('update/{school_id}','setupController@update_school')->name('setup.schools.update');
                Route::post('update_school_preference/{key}/{value}','setupController@update_school_preference')->name('setup.schools.update_preference');
            });
        });
        Route::get('status','setupController@status')->name('setup.status')->middleware('teamSA');
        Route::get('change-school/{school_id}','setupController@change_school_active')->name('setup.change-school');
        Route::get('change-academic-year/{academic_year_id}','setupController@change_academic_year_active')->name('setup.change-academic-year-active');

    });

    Route::group(['prefix' => 'my_account'], function() {
        Route::get('/', 'MyAccountController@edit_profile')->name('my_account');
        Route::put('/', 'MyAccountController@update_profile')->name('my_account.update');
        Route::put('/change_password', 'MyAccountController@change_pass')->name('my_account.change_pass');
    });

    /*************** Support Team *****************/
    Route::group(['namespace' => 'SupportTeam',], function(){

        /*************** Students *****************/
        Route::group(['prefix' => 'students'], function(){
            Route::get('reset_pass/{st_id}', 'StudentRecordController@reset_pass')->name('st.reset_pass');
            Route::get('graduated', 'StudentRecordController@graduated')->name('students.graduated');
            Route::put('not_graduated/{id}', 'StudentRecordController@not_graduated')->name('st.not_graduated');
            Route::get('list/{class_id}', 'StudentRecordController@listByClass')->name('students.list')->middleware('teamSAT');
            Route::get('import/{class_id?}','StudentRecordController@import')->name('students.import')->middleware('teamSA');
            Route::post('class','StudentRecordController@class_selector');
            Route::post('save/import','StudentRecordController@saveImportData');

            /* Promotions */
            Route::post('promote_selector', 'PromotionController@selector')->name('students.promote_selector');
            Route::get('promotion/manage', 'PromotionController@manage')->name('students.promotion_manage');
            Route::delete('promotion/reset/{pid}', 'PromotionController@reset')->name('students.promotion_reset');
            Route::delete('promotion/reset_all', 'PromotionController@reset_all')->name('students.promotion_reset_all');
            Route::get('promotion/{fc?}/{fs?}/{tc?}/{ts?}', 'PromotionController@promotion')->name('students.promotion');
            Route::post('promote/{fc}/{fs}/{tc}/{ts}', 'PromotionController@promote')->name('students.promote');

        });

        /*************** Users *****************/
        Route::group(['prefix' => 'users'], function(){
            Route::get('reset_pass/{user_id}', 'UserController@reset_pass')->name('users.reset_pass');
        });

        /*************** TimeTables *****************/
        Route::group(['prefix' => 'timetables'], function(){
            Route::get('/', 'TimeTableController@index')->name('tt.index');

            Route::group(['middleware' => 'teamSA'], function() {
                Route::post('/', 'TimeTableController@store')->name('tt.store');
                Route::put('/{tt}', 'TimeTableController@update')->name('tt.update');
                Route::delete('/{tt}', 'TimeTableController@delete')->name('tt.delete');
            });

            /*************** TimeTable Records *****************/
            Route::group(['prefix' => 'records'], function(){
                Route::group(['middleware' => 'teamSA'], function(){
                    Route::get('manage/{ttr}', 'TimeTableController@manage')->name('ttr.manage');
                    Route::post('/', 'TimeTableController@store_record')->name('ttr.store');
                    Route::get('edit/{ttr}', 'TimeTableController@edit_record')->name('ttr.edit');
                    Route::put('/{ttr}', 'TimeTableController@update_record')->name('ttr.update');
                });

                Route::get('show/{ttr}', 'TimeTableController@show_record')->name('ttr.show');
                Route::get('print/{ttr}', 'TimeTableController@print_record')->name('ttr.print');
                Route::delete('/{ttr}', 'TimeTableController@delete_record')->name('ttr.destroy');

            });

            /*************** Time Slots *****************/
            Route::group(['prefix' => 'time_slots', 'middleware' => 'teamSA'], function(){
                Route::post('/', 'TimeTableController@store_time_slot')->name('ts.store');
                Route::post('/use/{ttr}', 'TimeTableController@use_time_slot')->name('ts.use');
                Route::get('edit/{ts}', 'TimeTableController@edit_time_slot')->name('ts.edit');
                Route::delete('/{ts}', 'TimeTableController@delete_time_slot')->name('ts.destroy');
                Route::put('/{ts}', 'TimeTableController@update_time_slot')->name('ts.update');
            });

        });

        /*************** Payments *****************/
        Route::group(['prefix' => 'payments'], function(){

            Route::get('manage/{class_id?}','PaymentController@manage')->name('payments.manage');
            Route::get('invoice/{id}/{year?}', 'PaymentController@invoice')->name('payments.invoice');
            Route::get('receipts/{id}', 'PaymentController@receipts')->name('payments.receipts');
            Route::get('pdf_receipts/{id}', 'PaymentController@pdf_receipts')->name('payments.pdf_receipts');
            Route::post('select_year', 'PaymentController@select_year')->name('payments.select_year');
            Route::post('select_class', 'PaymentController@select_class')->name('payments.select_class');
            Route::delete('reset_record/{id}', 'PaymentController@reset_record')->name('payments.reset_record');
            Route::post('pay_now/{id}', 'PaymentController@pay_now')->name('payments.pay_now');
        });

        /*************** schools *****************/

        /*************** Pins *****************/
        Route::group(['prefix' => 'pins'], function(){
            Route::get('create', 'PinController@create')->name('pins.create');
            Route::get('/', 'PinController@index')->name('pins.index');
            Route::post('/', 'PinController@store')->name('pins.store');
            Route::get('enter/{id}', 'PinController@enter_pin')->name('pins.enter');
            Route::post('verify/{id}', 'PinController@verify')->name('pins.verify');
            Route::delete('/', 'PinController@destroy')->name('pins.destroy');
        });

        /*************** Marks *****************/
        Route::group(['prefix' => 'marks'], function(){
            Route::group(['middleware'=>['mark_status']],function(){

                // FOR teamSA
                    Route::group(['middleware' => 'teamSA'], function(){
                        Route::get('batch_fix', 'MarkController@batch_fix')->name('marks.batch_fix');
                        Route::put('batch_update', 'MarkController@batch_update')->name('marks.batch_update');
                        Route::get('tabulation/{exam?}/{class?}/{sec_id?}', 'MarkController@tabulation')->name('marks.tabulation');
                        Route::post('tabulation', 'MarkController@tabulation_select')->name('marks.tabulation_select');
                        Route::get('tabulation/print/{exam}/{class}/{sec_id}', 'MarkController@print_tabulation')->name('marks.print_tabulation');
                    });

                    // FOR teamSAT
                    Route::group(['middleware' => 'teamSAT'], function(){
                        Route::get('/', 'MarkController@index')->name('marks.index');
                        Route::get('manage/{exam}/{class}/{section}/{subject}', 'MarkController@manage')->name('marks.manage');
                        Route::put('update/{exam}/{class}/{section}/{subject}', 'MarkController@update')->name('marks.update');
                        Route::put('comment_update/{exr_id}', 'MarkController@comment_update')->name('marks.comment_update');
                        Route::put('skills_update/{skill}/{exr_id}', 'MarkController@skills_update')->name('marks.skills_update');
                        Route::post('selector', 'MarkController@selector')->name('marks.selector');
                        Route::get('bulk/{class?}/{section?}', 'MarkController@bulk')->name('marks.bulk');
                        Route::post('bulk', 'MarkController@bulk_select')->name('marks.bulk_select');
                        Route::group(['prefix'=>'setup'],function(){
                            Route::get('manage-skills','MarkController@manageSkills')->name('marks.setup.manage-skills');
                            Route::get('preferences/{marking_period_id?}','markController@preferences')->name('marks.setup.preferences');
                            Route::post('preferences-select','markController@preferences_select')->name('marks.setup.preferences-select');
                            Route::post('update-preferences/{marking_period_id?}','markController@preferences_update')->name('marks.setup.preferences-update');
                            Route::post('update/{skill_id}','markController@updateSkill');
                            Route::post('delete/{skill_id}','markController@deleteSkill');
                            Route::post('skill-type/update/{skill_type_id}','markController@updateSkillType');
                            Route::post('skill-type/delete/{skill_type_id}','markController@deleteSkillType');
                            Route::post('add-skill','markController@addSkill')->name('marks.setup.add-skill');
                            Route::post('add-skill-type','markController@addSkillType');
                            Route::get('manage-skills-type','MarkController@manageSkillsType')->name('marks.setup.manage-skills-type');
                            Route::get('remarks','MarkController@manageRemarks')->name('marks.setup.manage-remarks');
                            Route::post('add-remark','MarkController@addRemark')->name('marks.setup.add-remark');
                            Route::post('remark/update/{remark_id}','MarkController@updateRemark');
                            Route::post('remark/delete/{remark_id}','MarkController@deleteRemark');
                        });
                    });
                    // for general users
                    Route::get('select_year/{student_id}', 'MarkController@year_selector')->name('marks.year_selector');
                    Route::post('select_year/{student_id}', 'MarkController@year_selected')->name('marks.year_select');
                    Route::get('show/{student_id}/{year}', 'MarkController@show')->name('marks.show');
                    Route::get('print/{student_id}/{exam_id}/{year}', 'MarkController@print_view')->name('marks.print');
            });
            Route::get('status','MarkController@status')->name('marks.status');

        });

        Route::group(['prefix'=>'resource'],function(){
            // FOR TEAM SA
            Route::group(['middleware'=>'teamSA'],function(){
                // Route::delete('/remove-resource','')
            });
            // FOR TEAM SAT
            Route::group(['middleware'=>'teamSAT'],function(){
                Route::post('create','ResourceController@create');
                Route::delete('delete/{resource_id}','ResourceController@delete');
                Route::get('edit/{resource_id}','ResourceController@edit');
                Route::put('update/{resource_id}','ResourceController@update');
            });
            // FOR all users
            Route::get('/','ResourceController@index')->name('resource.index');
            Route::get('/all','ResourceController@all');
            Route::get('/{resource_id}','ResourceController@resource_by_id');
        });
        Route::resource('students', 'StudentRecordController');
        Route::resource('users', 'UserController');
        Route::resource('setup','setupController');
        Route::resource('classes', 'MyClassController');
        Route::resource('sections', 'SectionController');
        Route::resource('subjects', 'SubjectController');
        Route::resource('grades', 'GradeController');
        Route::resource('exams', 'ExamController');
        Route::resource('dorms', 'DormController');
        Route::resource('payments', 'PaymentController');

    });

    /************************ AJAX ****************************/
    Route::group(['prefix' => 'ajax'], function() {
        Route::get('get_lga/{state_id}', 'AjaxController@get_lga')->name('get_lga');
        Route::get('get_class_sections/{class_id}', 'AjaxController@get_class_sections')->name('get_class_sections');
        Route::get('get_class_subjects/{class_id}', 'AjaxController@get_class_subjects')->name('get_class_subjects');
    });

});

/************************ SUPER ADMIN ****************************/
Route::group(['namespace' => 'SuperAdmin','middleware' => 'super_admin', 'prefix' => 'super_admin'], function(){

    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::put('/settings', 'SettingController@update')->name('settings.update');

});

/************************ PARENT ****************************/
Route::group(['namespace' => 'MyParent','middleware' => 'my_parent',], function(){

    Route::get('/my_children', 'MyController@children')->name('my_children');

});
