
@php
    $url = '';

    $MyNavBar = \Menu::make('MenuList', function ($menu) use($url){

        $menu->raw('<h6>'.__('message.dashboard').'</h6>');
        $menu->add('<span>'.__('message.dashboard').'</span>', ['route' => 'home'])
            ->prepend('<i class="fas fa-home"></i>')
            ->link->attr(['class' => '']);
     
            if (collect(['category-list', 'customtopic-list','sections-list','imagesection-list','common-question-list'])->some(fn($perm) => auth()->user()->can($perm))) {
              $menu->raw('<h6>'.__('message.categories').'</h6>');
            }

        $menu->add('<span>'.__('message.category').'</span>', ['class' => ''])
                ->prepend('<i class="fas fa-project-diagram"></i>')
                ->nickname('category')
                ->data('permission', 'category-list')
                ->link->attr(['class' => ''])
                ->href('#category');

            $menu->category->add('<span>'.__('message.list_form_title',['form' => __('message.category')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'category.index'])
                ->data('permission', 'category-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

            $menu->category->add('<span>'.__('message.list_form_title',['form' => __('message.topic')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'topic.index'])
                ->data('permission', 'category-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

            $menu->category->add('<span>'.__('message.list_form_title',['form' => __('message.custom_topic')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'customtopic.index'])
                ->data('permission', 'customtopic-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

            $menu->category->add('<span>'.__('message.need_help').'</span>', ['class' => 'sidebar-layout' ,'route' => 'category.needhelp'])
                ->data('permission', '')
                ->prepend('<i class="fas fa-question-circle"></i>')
                ->link->attr(['class' => 'nav-link' ]);

        $menu->add('<span>'.__('message.category_sections').'</span>', ['class' => ''])
            ->prepend('<i class="fas fa-folder-open"></i>')
            ->nickname('sections')
            ->data('permission', 'sections-list')
            ->link->attr(['class' => ''])
            ->href('#sections');

            $menu->sections->add('<span>'.__('message.list_form_title',['form' => __('message.image_section')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'image-section.index'])
                ->data('permission', 'imagesection-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

            $menu->sections->add('<span>'.__('message.list_form_title',['form' => __('message.info_section')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'sections.index'])
                ->data('permission', 'sections-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

            $menu->sections->add('<span>'.__('message.list_form_title',['form' => __('message.common_que_ans')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'common-question.index'])
                ->data('permission', 'common-question-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

            $menu->sections->add('<span>'.__('message.need_help').'</span>', ['class' => 'sidebar-layout' ,'route' => 'image.needhelp'])
                ->data('permission', '')
                ->prepend('<i class="fas fa-question-circle"></i>')
                ->link->attr(['class' => 'nav-link' ]);

                if (collect(['sections-data-main-list', 'symptoms-list','sections-list','sub-symptoms-list','default-log-category-list','health-experts-list','askexpert-list'])->some(fn($perm) => auth()->user()->can($perm))) {
                    $menu->raw('<h6>'.__('message.health_wellness').'</h6>');
                }

        $menu->add('<span>'.__('message.self_care').'</span>', ['class' => ''])
                    ->prepend('<i class="fa fa-hand-holding-medical"></i>')
                    ->nickname('self_care')
                    ->data('permission', 'sections-list')
                    ->link->attr(['class' => ''])
                    ->href('#self_care');

            $menu->self_care->add('<span>'.__('message.list_form_title',['form' => __('message.self_care')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'section-data-main.index'])
                    ->data('permission', 'sections-data-main-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => 'nav-link' ]);

            $menu->self_care->add('<span>'.__('message.need_help').'</span>', ['class' => 'sidebar-layout' ,'route' => 'selfcare.needhelp'])
                ->data('permission', '')
                ->prepend('<i class="fas fa-question-circle"></i>')
                ->link->attr(['class' => 'nav-link' ]);

                $menu->add('<span>'.__('message.symptoms').'</span>', ['class' => ''])
                ->prepend('<i class="fas fa-star-of-life"></i>')
                ->nickname('symptoms')
                ->data('permission', 'symptoms-list')
                ->link->attr(['class' => ''])
                ->href('#symptoms');

            $menu->symptoms->add('<span>'.__('message.list_form_title',['form' => __('message.symptoms')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'symptoms.index'])
                ->data('permission', 'symptoms-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);

            $menu->symptoms->add('<span>'.__('message.list_form_title',['form' => __('message.sub_symptoms')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'sub-symptoms.index'])
                ->data('permission', 'sub-symptoms-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);

            $menu->symptoms->add('<span>'.__('message.list_form_title',['form' => __('message.default_log_category')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'default-log-category.index'])

                ->data('permission', 'default-log-category-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);

        $menu->add('<span>'.__('message.health_experts').'</span>', ['class' => ''])
                ->prepend('<i class="fas fa-user-md"></i>')
                ->nickname('health_experts')
                ->data('permission', 'health-experts-list')
                ->link->attr(['class' => ''])
                ->href('#health_experts');

            $menu->health_experts->add('<span>'.__('message.list_form_title',['form' => __('message.health_experts')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'health-experts.index'])
                ->data('permission', 'health-experts-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);


        $menu->add('<span>'.__('message.askexpert').'</span>', ['class' => ''])
             ->prepend('<i class="fa fa-question"></i>')
             ->nickname('askexpert')
             ->data('permission', 'askexpert-list')
             ->link->attr(['class' => ''])
             ->href('#askexpert');

             $menu->askexpert->add('<span>'.__('message.list_form_title',['form' => __('message.askexpert')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'ask-expert.index'])
                 ->data('permission', 'askexpert-list')
                 ->prepend('<i class="fas fa-list"></i>')
                   ->link->attr(['class' => '']);


            if (collect(['insights-list', 'cycledays-list','pregnancydate-list','personalinsights-list','dailyinsight-list','calculatortool-list'])->some(fn($perm) => auth()->user()->can($perm))) {
                $menu->raw('<h6>'.__('message.insight_tool').'</h6>');
            }

    $menu->add('<span>'.__('message.insights').'</span>', ['class' => ''])
            ->prepend('<i class="fas fa-rss-square"></i>')
            ->nickname('insights')
            ->data('permission', 'insights-list')
            ->link->attr(['class' => ''])
            ->href('#insights');

            $menu->insights->add('<span>'.__('message.list_form_title',['form' => __('message.based_on_symptoms')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'insights.index'])
                ->data('permission', 'insights-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);

                $menu->insights->add('<span>'.__('message.list_form_title',['form' => __('message.based_on_cycleday')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'cycle-dates.index'])
                ->data('permission', 'cycledays-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);

                $menu->insights->add('<span>'.__('message.week_form_title',['form' => __('message.based_on_pregnancy')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'pregnancy-week.index'])
                ->data('permission', 'pregnancydate-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

                $menu->insights->add('<span>'.__('message.list_form_title',['form' => __('message.personalinsights')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'personalinsights.index'])
                ->data('permission', 'personalinsights-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

                 $menu->insights->add('<span>'. __('message.daily_insight_tips').'</span>', ['class' => 'sidebar-layout' ,'route' => 'dailyInsight.index'])
                ->data('permission', 'dailyinsight-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);

                $menu->insights->add('<span>'.__('message.need_help').'</span>', ['class' => 'sidebar-layout' ,'route' => 'insights.needhelp'])
                ->data('permission', '')
                ->prepend('<i class="fas fa-question-circle"></i>')
                ->link->attr(['class' => 'nav-link' ]);

    $menu->add('<span>'.__('message.calculator_tool').'</span>', ['class' => ''])
            ->prepend('<i class="fas fa-balance-scale-right"></i>')
            ->nickname('calculatortool')
            ->data('permission', 'calculatortool-list')
            ->link->attr(['class' => ''])
            ->href('#calculatortool');

            $menu->calculatortool->add('<span>'.__('message.list_form_title',['form' => __('message.calculator_tool')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'calculator-tool.index'])
                ->data('permission', 'calculatortool-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => 'nav-link' ]);

            if (collect(['user-list','user-add','user-edit','sub_admin-list'])->some(fn($perm) => auth()->user()->can($perm))) {
                $menu->raw('<h6>'.__('message.user_management').'</h6>');
            }

        $menu->add('<span>'.__('message.users').'</span>', ['route' => 'users.index'])
            ->prepend('<i class="fas fa-user"></i>')
            ->nickname('user')
            ->data('permission', 'user-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#user');

            $menu->user->add( '<span>'.__('message.all_user').'</span>', ['class' => request()->is('users/*/add') ? 'sidebar-layout active' : 'sidebar-layout' ,'route' => 'users.index'])
                ->data('permission', ['user-add','user-edit'])
                ->prepend('<i class="fa fa-user-tie"></i>')
                ->link->attr(['class' => '']);

            $menu->user->add('<span>' . __('message.active_list_form_title', ['form' => __('message.users')]) . '</span>',['class' => 'sidebar-layout', 'route' => ['user.status', 'active']])
                ->data('permission', 'user-list')
                ->prepend('<i class="fa fa-user-check"></i>')
                ->link->attr(['class' => '']);

            $menu->user->add('<span>' . __('message.inactive_list_form_title', ['form' => __('message.users')]) . '</span>',['class' => 'sidebar-layout', 'route' => ['user.status', 'inactive']])
                ->data('permission', 'user-list')
                ->prepend('<i class="fa fa-user-clock"></i>')
                ->link->attr(['class' => '']);

        $menu->add('<span>'.__('message.anonymous_user').'</span>', ['route' => 'anonymoususer.index'])
            ->prepend('<i class="fa fa-user-nurse"></i>')
            ->nickname('anonymoususer')
            ->data('permission', 'user-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#anonymoususer');

            $menu->anonymoususer->add( '<span>'.__('message.all_anonymous_user').'</span>', ['class' => request()->is('anonymoususer/*/add') ? 'sidebar-layout active' : 'sidebar-layout' ,'route' => 'anonymoususer.index'])
                ->data('permission', ['user-add','user-edit'])
                ->prepend('<i class="fa fa-user-tie"></i>')
                ->link->attr(['class' => '']);

            $menu->anonymoususer->add('<span>' . __('message.active_list_form_title', ['form' => __('message.anonymous_user')]) . '</span>',['class' => 'sidebar-layout', 'route' => ['anonymoususer.status', 'active']])
                ->data('permission', 'user-list')
                ->prepend('<i class="fa fa-user-check"></i>')
                ->link->attr(['class' => '']);

            $menu->anonymoususer->add('<span>' . __('message.inactive_list_form_title', ['form' => __('message.anonymous_user')]) . '</span>',['class' => 'sidebar-layout', 'route' => ['anonymoususer.status', 'inactive']])
                ->data('permission', 'user-list')
                ->prepend('<i class="fa fa-user-clock"></i>')
                ->link->attr(['class' => '']);

                $menu->add('<span>'.__('message.sub_admin').'</span>', ['class' => ''])
                ->prepend('<i class="fa fa-users"></i>')
                ->nickname('sub_admin')
                ->data('permission', 'sub_admin-list')
                ->link->attr(['class' => ''])
                ->href('#sub_admin');

            $menu->sub_admin->add('<span>'.__('message.add_form_title',['form' => __('message.sub_admin')]).'</span>', ['class' => request()->is('sub-admin/*/edit') ? 'sidebar-layout active' : 'sidebar-layout' ,'route' => 'sub-admin.create'])
                ->data('permission', [ 'sub_admin-add', 'sub_admin-edit'])
                ->prepend('<i class="fas fa-plus-square"></i>')
                ->link->attr(['class' => '']);

            $menu->sub_admin->add('<span>'.__('message.list_form_title',['form' => __('message.sub_admin')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'sub-admin.index'])
                ->data('permission', 'sub_admin-list')
                ->prepend('<i class="fas fa-list"></i>')
                ->link->attr(['class' => '']);

                if (collect(['article-list', 'tags-list','pregnancydate-list','pages','terms condition','privacy policy'])->some(fn($perm) => auth()->user()->can($perm))) {
                       $menu->raw('<h6>'.__('message.article_pages').'</h6>');
                }

            $menu->add('<span>'.__('message.article').'</span>', ['class' => ''])
                ->prepend('<i class="fas fa-file-alt"></i>')
                ->nickname('article')
                ->data('permission', 'article-list')
                ->link->attr(['class' => ''])
                ->href('#article');

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.tags')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'tags.index'])
                    ->data('permission', 'tags-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.article')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'article.index'])
                    ->data('permission', 'article-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.menstrual_phase')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['article.index','article_type'=>'menstrual_phase']])
                    ->data('permission', 'article-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.follicular_phase')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['article.index','article_type'=>'follicular_phase']])
                    ->data('permission', 'article-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.ovulation_phase')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['article.index','article_type'=>'ovulation_phase']])
                    ->data('permission', 'article-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.luteal_phase')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['article.index','article_type'=>'luteal_phase']])
                    ->data('permission', 'article-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.late_period')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['article.index','article_type'=>'late_period']])
                    ->data('permission', 'article-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.list_form_title',['form' => __('message.pregnancy')]).'</span>', ['class' => 'sidebar-layout' ,'route' => ['article.index','article_type'=>'pregnancy']])
                    ->data('permission', 'article-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->article->add('<span>'.__('message.week_form_title',['form' => __('message.based_on_pregnancy')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'pregnancy-date.index'])
                    ->data('permission', 'pregnancydate-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => 'nav-link' ]);


            $menu->add('<span>'.__('message.pages').'</span>', ['class' => ''])
                    ->prepend('<i class="fas fa-file"></i>')
                    ->nickname('pages')
                    ->data('permission', 'pages')
                    ->link->attr(['class' => ''])
                    ->href('#pages');

                $menu->pages->add('<span>'.__('message.terms_condition').'</span>', ['class' => 'sidebar-layout' ,'route' => 'term-condition'])
                    ->data('permission', 'terms condition')
                    ->prepend('<i class="fas fa-file-contract"></i>')
                    ->link->attr(['class' => '']);

                $menu->pages->add('<span>'.__('message.privacy_policy').'</span>', ['class' => 'sidebar-layout' ,'route' => 'privacy-policy'])
                    ->data('permission', 'privacy policy')
                    ->prepend('<i class="fas fa-user-shield"></i>')
                    ->link->attr(['class' => '']);

                    if (collect(['uploadvideos-list', 'faq-list'])->some(fn($perm) => auth()->user()->can($perm))) {
                        $menu->raw('<h6>'.__('message.media_content').'</h6>');
                    }

            $menu->add('<span>'.__('message.videos_list').'</span>', ['class' => ''])
                ->prepend('<i class="fas fa-file-alt"></i>')
                ->nickname('upload_videos')
                ->data('permission', 'uploadvideos-list')
                ->link->attr(['class' => ''])
                ->href('#upload_videos');

                $menu->upload_videos->add('<span>'.__('message.list_form_title',['form' => __('message.videos_list')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'upload-videos.index'])
                    ->data('permission', 'uploadvideos-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

            $menu->add('<span>'.__('message.faq').'</span>', ['class' => ''])
                ->prepend('<i class="far fa-question-circle"></i>')
                ->nickname('faq')
                ->data('permission', 'faq-list')
                ->link->attr(['class' => ''])
                ->href('#faq');

                $menu->faq->add('<span>'.__('message.list_form_title',['form' => __('message.faq')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'faqs.index'])
                    ->data('permission', 'faq-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => 'nav-link' ]);

                    if (collect(['usubscription-list'])->some(fn($perm) => auth()->user()->can($perm))) {
                        $menu->raw('<h6>'.__('message.subscription_management').'</h6>');
                    }

            $menu->add('<span>'.__('message.pushnotification').'</span>', ['class' => ''])
                        ->prepend('<i class="fas fa-bullhorn"></i>')
                        ->nickname('pushnotification')
                        ->data('permission', 'push notification-list')
                        ->link->attr(['class' => ''])
                        ->href('#pushnotification');

                $menu->pushnotification->add('<span>'.__('message.list_form_title',['form' => __('message.pushnotification')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'pushnotification.index'])
                    ->data('permission', 'push notification-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->pushnotification->add('<span>'.__('message.add_form_title',['form' => __('message.pushnotification')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'pushnotification.create'])
                    ->data('permission', [ 'push notification-add', 'push notification-edit'])
                    ->prepend('<i class="fas fa-plus-square"></i>')
                    ->link->attr(['class' => '']);

                    if (collect(['role-list','permission-list','languagelist-list','screen-list','languagewithkeyword-list','bulkimport-add','system-setting'])->some(fn($perm) => auth()->user()->can($perm))) {
                        $menu->raw('<h6>'.__('message.app_lanuage_settiings').'</h6>');
                    }


            $menu->add('<span>'.__('message.account_setting').'</span>', ['class' => ''])
                    ->prepend('<i class="fas fa-users-cog"></i>')
                    ->nickname('account_setting')
                    ->data('permission', ['role-list','permission-list'])
                    ->link->attr(["class" => ""])
                    ->href('#account_setting');

                $menu->account_setting->add('<span>'.__('message.list_form_title',['form' => __('message.role')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'role.index'])
                    ->data('permission', 'role-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                   
                $menu->account_setting->add('<span>'.__('message.list_form_title',['form' => __('message.permission')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'permission.index'])
                    ->data('permission', 'permission-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);
                  
            
                $menu->add('<span>'.__('message.app_language_setting').'</span>', [ 'class' => ''])
                        ->prepend('<i class="fa fa-language"></i>')
                        ->nickname('app_language_setting')
                        ->data('permission', '')
                        ->link->attr(['class' => ''])
                        ->href('#app_language_setting');

                $menu->app_language_setting->add('<span>'.__('message.list_form_title',['form' => __('message.screen')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'screen.index'])
                    ->data('permission', 'screen-list')
                    ->prepend('<i class="fas fa-list"></i>')
                    ->link->attr(['class' => '']);

                $menu->app_language_setting->add('<span>'.__('message.list_form_title',['form' => __('message.default_keyword')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'defaultkeyword.index'])
                        ->data('permission', 'defaultkeyword-list')
                        ->prepend('<i class="fas fa-list"></i>')
                        ->link->attr(['class' => '']);

                $menu->app_language_setting->add('<span>'.__('message.list_form_title',['form' => __('message.language')]).'</span>', ['class' => request()->is('languagelist/*/edit') || request()->is('languagelist/create') ? 'sidebar-layout active' : 'sidebar-layout' ,'route' => 'languagelist.index'])
                        ->data('permission', 'languagelist-list')
                        ->prepend('<i class="fas fa-list"></i>')
                        ->link->attr(['class' => '']);

                $menu->app_language_setting->add('<span>'.__('message.list_form_title',['form' => __('message.language_with_keyword')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'languagewithkeyword.index'])
                        ->data('permission', 'languagewithkeyword-list')
                        ->prepend('<i class="fas fa-list"></i>')
                        ->link->attr(['class' => '']);

                $menu->app_language_setting->add('<span>'.__('message.list_form_title',['form' => __('message.bulk_import_langugage_data')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'bulk.language.data'])
                            ->data('permission', 'bulkimport-add')
                            ->prepend('<i class="fas fa-list"></i>')
                            ->link->attr(['class' => '']);

            $menu->add('<span>'.__('message.setting').'</span>', ['route' => 'setting.index'])
                    ->prepend('<i class="fas fa-cog"></i>')
                    ->nickname('setting')
                    ->data('permission', 'system-setting');
                    

            if(Module::has('Frontend') && Module::isEnabled('Frontend')) {
                
                $menu->raw('<h6>'.__('frontend::message.website_section').'</h6>');

                    $menu->add('<span>'.__('frontend::message.website_section').'</span>', [ 'class' => ''])
                            ->prepend('<i class="fa fa-window-maximize"></i>')
                            ->nickname('website_section')
                            ->data('permission', 'website-section-list')
                            ->link->attr(['class' => ''])
                            ->href('#website_section');

                    $menu->website_section->add('<span>' . __('message.information') . '</span>',['class' => 'sidebar-layout','route' => ['frontend.website.form', 'app-info']])
                            ->data('permission', 'website-section-list')
                            ->prepend('<i class="fas fa-list"></i>')
                            ->link->attr(['class' => '']);

                    // $menu->website_section->add('<span>' . __('frontend::message.newsletter') . '</span>',['class' => 'sidebar-layout','route' => ['frontend.website.form', 'newsletter']])
                    //         ->data('permission', 'website-section-list')
                    //         ->prepend('<i class="fas fa-list"></i>')
                    //         ->link->attr(['class' => '']);

                    // $menu->website_section->add('<span>'.__('message.list_form_title',['form' => __('frontend::message.subscribers')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'subscribers.index'])
                    //         ->data('permission', 'website-section-list')
                    //         ->prepend('<i class="fas fa-list"></i>')
                    //         ->link->attr(['class' => '']);

                    $menu->website_section->add('<span>' . __('frontend::message.user_review') . '</span>',['class' => 'sidebar-layout','route' => ['frontend.website.form', 'user-review']])
                            ->data('permission', 'website-section-list')
                            ->prepend('<i class="fas fa-list"></i>')
                            ->link->attr(['class' => '']);

                    $menu->website_section->add('<span>'.__('message.list_form_title',['form' => __('frontend::message.app_overview')]).'</span>', ['class' => 'sidebar-layout' ,'route' => 'app-overview.index'])
                        ->data('permission', 'website-section-list')
                        ->prepend('<i class="fas fa-list"></i>')
                        ->link->attr(['class' => '']);
            }

            if (env('ACTIVITY_LOG_ENABLED') == true) {
                $menu->add('<span>'.__('message.manage_history').'</span>', ['route' => 'activity.history'])
                ->prepend('<i class="fas fa-history"></i>')
                ->data('permission', 'system setting')
                ->link->attr(['class' => '']);
            }
                    
        })->filter(function ($item) {
            return checkMenuRoleAndPermission($item);
        });
@endphp

<div class="mm-sidebar sidebar-default">
    <div class="mm-sidebar-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="header-logo">
            <img src="{{ getSingleMedia(appSettingData('get'),'site_logo',null) }}" class="img-fluid mode light-img rounded-normal light-logo site_logo_preview" alt="logo">
            <img src="{{ getSingleMedia(appSettingData('get'),'site_dark_logo',null) }}" class="img-fluid mode dark-img rounded-normal darkmode-logo site_dark_logo_preview" alt="dark-logo">
        </a>
        <div class="side-menu-bt-sidebar p-0">
            <i class="fas fa-bars wrapper-menu p-1"></i>
        </div>
    </div>
    <div class="mm-sidebar-logo d-flex mm-search-bar device-search mm-sidebar-menu-search m-auto">
        <div class="searchbox">
            <i class="ri-search-line search-link"></i>
            <input type="text" class="text search-input" placeholder="{{ __('message.search_menu') }}">
        </div>
    </div>
    <div class="data-scrollbar pb-5" data-scroll="1">
        <nav class="mm-sidebar-menu">
            <ul id="mm-sidebar-toggle" class="side-menu">
                @include(config('laravel-menu.views.bootstrap-items'), ['items' => $MyNavBar->roots()])
            </ul>
        </nav>
        <div class="pt-5 pb-5 mb-5"></div>
    </div>
</div>
