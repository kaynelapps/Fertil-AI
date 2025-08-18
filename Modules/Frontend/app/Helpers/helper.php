<?php
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

function DummyData($key){
    $dummy_title = 'XXXXXXXXXXXX';
    $dummy_description = 'xxxxxxxxxxxxxxxx xxxxxxxxx xxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxxxxxxxxxxxxxx xxxxxxxxx xxxxxxxxxxx xxxxxxxxxxxxxxxxx';

    switch ($key) {
        case 'dummy_title':
            return $dummy_title;
        case 'dummy_description':
            return $dummy_description;
        default:
            return '';
    }
}

function encryptDecryptId($request_id = null,$type = null)
{
    if ($type == 'encrypt') {
        return $request_id ? Crypt::encryptString($request_id) : null;
    }
    
    if ($type === 'decrypt') {
        return $request_id ? Crypt::decryptString($request_id) : null;
    }

    return null;
}

function getSingleMediaSettingImage($model = null, $collection_name, $check_collection_type = null) {
    $image = null;
    
    if ($model !== null) {
        $image = $model->getFirstMedia($collection_name);
    }

    if ($image !== null && getFileExistsCheck($image))
    {
        return $image->getFullUrl();
    }else{
        switch ($collection_name) {
            case 'logo_image':
            case 'article_image':
            case 'health_experts_image':
            case 'calculator_thumbnail_image':
            case 'user-review':
                $image = asset('frontend-section/images/default.png');
                break;
            case 'image':
                switch ($check_collection_type) {
                    // case 'app-info':
                    //     $image = asset('frontend-section/images/default.png');
                    //     break;
                }
            break;
        }
        return $image;
    }
}

function getSettingFirstData($type = null, $key = null)
{
    return Setting::where('type', $type)->where('key', $key)->first();
}

function renderStars($rating) {
    $rating = is_numeric($rating) ? min(5, max(0, $rating)) : 0;        
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;

    return str_repeat('<svg class="m-1" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.13706 0.632812L11.1885 6.94636H17.8269L12.4563 10.8484L14.5077 17.1619L9.13706 13.2599L3.76643 17.1619L5.81783 10.8484L0.447199 6.94636H7.08566L9.13706 0.632812Z" fill="var(--site-color)"/></svg>', $fullStars) .
            ($halfStar ? '<svg class="m-1" width="18" height="18" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.23374 1.32853L11.0807 7.0128L11.1289 7.16135H11.2851H17.2619L12.4266 10.6744L12.3002 10.7662L12.3485 10.9148L14.1954 16.5991L9.3601 13.086L9.23374 12.9942L9.10737 13.086L4.27204 16.5991L6.11897 10.9148L6.16724 10.7662L6.04087 10.6744L1.20555 7.16135H7.18234H7.33854L7.38681 7.0128L9.23374 1.32853Z" stroke="var(--site-color)" stroke-width="0.429979"/><mask id="mask0_293_3300" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="18" height="18"><path d="M9.23374 0.632812L11.2851 6.94636H17.9236L12.553 10.8484L14.6044 17.1619L9.23374 13.2599L3.86311 17.1619L5.91451 10.8484L0.543879 6.94636H7.18234L9.23374 0.632812Z" fill="var(--site-color)"/></mask><g mask="url(#mask0_293_3300)"><rect x="0.419189" y="-6.46191" width="9.45954" height="38.3756" fill="var(--site-color)"/></g></svg>' : '') .
            str_repeat('<svg class="m-1" width="18" height="18" viewBox="0 0 42 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 1.61804L25.4638 15.3561L25.576 15.7016H25.9393H40.3844L28.6981 24.1922L28.4042 24.4058L28.5164 24.7513L32.9802 38.4894L21.2939 29.9987L21 29.7852L20.7061 29.9987L9.01978 38.4894L13.4836 24.7513L13.5958 24.4058L13.3019 24.1922L1.6156 15.7016H16.0607H16.424L16.5362 15.3561L21 1.61804Z" stroke="#8A8A8A"/></svg>', $emptyStars);
}

function convertToEmbedUrl($url) {
    if (Str::contains($url, 'youtu.be')) {
        $videoId = Str::after($url, 'youtu.be/');
        return 'https://www.youtube.com/embed/' . $videoId;
    } elseif (Str::contains($url, 'youtube.com/watch')) {
        $videoId = Str::after($url, 'watch?v=');
        return 'https://www.youtube.com/embed/' . $videoId;
    } elseif (Str::contains($url, 'vimeo.com')) {
        $videoId = Str::afterLast($url, '/');
        return 'https://player.vimeo.com/video/' . $videoId;
    }
    return $url;
}