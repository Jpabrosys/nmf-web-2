<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\VideoFileController;
use App\Http\Controllers\ReelVideoController;
use App\Http\Controllers\WebStoryController;
use App\Http\Controllers\WebStoryFileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RashiphalController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuSequenceController;
use App\Http\Controllers\TopNewsController;
use App\Http\Controllers\TrendingTopicTagController;
use App\Http\Controllers\StateSequenceController;
use App\Http\Controllers\BannerHomePageController;
use App\Http\Controllers\HomeCategorySequence;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\VoteOptionController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RSSFeedController;
use App\Http\Controllers\BigEventController;
use App\Http\Controllers\CommentController;

use App\Http\Controllers\Auth\ViewerSocialController;
use App\Services\ExportHome;
use App\Http\Controllers\ElectionResultController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\MahamukablaController;
use App\Http\Controllers\PartyController;


Route::get('/bihar-election-2025-phase-1', [StoryController::class, 'biharphaseone'])->name('biharphaseone');
Route::get('/bihar-election-2025-phase-2', [StoryController::class, 'biharphasetwo'])->name('biharphasetwo');
Route::get('/clear-cache', function () {
    if (request()->input('key') !== env('CACHE_CLEAR_KEY')) {
        abort(403);
    }

    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('clear-compiled');

    return 'All caches cleared!';
});

Route::get('/auth/google', [ViewerSocialController::class, 'redirectToGoogle'])->name('auth.google');


Route::prefix('admin')->name('admin.')->group(function () {

    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CommentModerationController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\CommentModerationController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [\App\Http\Controllers\Admin\CommentModerationController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [\App\Http\Controllers\Admin\CommentModerationController::class, 'update'])->name('update');
        Route::post('/approve/{id}', [\App\Http\Controllers\Admin\CommentModerationController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [\App\Http\Controllers\Admin\CommentModerationController::class, 'reject'])->name('reject');
        Route::post('/delete/{id}', [\App\Http\Controllers\Admin\CommentModerationController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [\App\Http\Controllers\Admin\CommentModerationController::class, 'bulkAction'])->name('bulkAction');
    });

});



// Viewer email/password login (optional)
Route::prefix('viewer')->name('viewer.')->group(function () {
    // Route::get('login',  [ViewerLoginController::class,'showLoginForm'])->name('login');
    // Route::post('login', [ViewerLoginController::class,'login'])->name('login.submit');
    // Route::post('logout',[ViewerLoginController::class,'logout'])->name('logout');

    // Google OAuth
    Route::get('auth/google/redirect', [ViewerSocialController::class, 'redirectToGoogle'])
        ->name('google.redirect');
    Route::get('auth/google/callback', [ViewerSocialController::class, 'handleGoogleCallback'])
        ->name('google.callback');

    // Logout route
    Route::get('logout', [ViewerSocialController::class, 'logout'])->name('logout');

    // Viewer-protected home (for redirect)
    Route::get('/', [App\Http\Controllers\HomeController::class, 'homePage'])
        ->middleware('auth.viewer')->name('home');
});

// Route::post('/comments', [CommentSection::class, 'postComment'])
//     ->name('comments.store')
//     ->middleware('auth:viewer');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth:viewer');
Route::post('/comments/reply', [CommentController::class, 'reply'])->name('comments.reply');
Route::post('/comments/{id}/like', [CommentController::class, 'toggleLike'])->name('comments.like');




Route::get('/', [HomeController::class, 'homePage'])->name('homePage');

Route::get('/backend-login-nmfteam', [LoginController::class, 'showLoginForm'])->name('custom.login');
Route::post('/backend-login-nmfteam', [LoginController::class, 'login'])->name('custom.login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// NL1020:12Sep2025:Added:Start:After login
// add a login alias so auth middleware works
Route::get('/login', function () {
    return redirect()->route('custom.login');
})->name('login');

// protected export route
//Route::get('/internal/export-home', [ExportController::class, 'export']);

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/export-home', function () {
        //Artisan::call('export:home');
        app(ExportHome::class)->run();
        return response('Home page exported!', 200);
    })->name('admin.export.home');
});
// NL1020:12Sep2025:Added:End

Route::fallback(function () {
    return view('error');
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::prefix('/files')->group(function () {
    Route::get('/', [FileController::class, 'index'])->name('fileList');
    Route::get('/add', [FileController::class, 'fileAdd'])->name('fileAdd');
    Route::post('/add', [FileController::class, 'addFile'])->name('addFile');
    Route::get('/edit/{id}', [FileController::class, 'editFile'])->name('editFile');
    Route::post('/edit/{id}', [FileController::class, 'fileEdit'])->name('fileEdit');
    Route::post('/upload', [FileController::class, 'uploadFile'])->name('uploadFile');
    Route::get('/delete/{id}', [FileController::class, 'del'])->name('deleteBoxFile');
    Route::get('/del/{id}', [FileController::class, 'deleteFile'])->name('deleteFile');
});

Route::prefix('/events')->group(function () {
    Route::get('/', [BigEventController::class, 'index'])->name('eventList');
    Route::get('/add', [BigEventController::class, 'eventAdd'])->name('eventAdd');
    Route::post('/add', [BigEventController::class, 'addEvent'])->name('addEvent');
    Route::get('/edit/{id}', [BigEventController::class, 'editEvent'])->name('editEvent');
    Route::post('/edit/{id}', [BigEventController::class, 'eventEdit'])->name('eventEdit');
    Route::get('/delete/{id}', [BigEventController::class, 'del'])->name('deleteBoxEvent');
    Route::get('/del/{id}', [BigEventController::class, 'deleteEvent'])->name('deleteEvent');

    Route::get('/event-blogs/{id}', [BigEventController::class, 'eventBlogs'])->name('eventBlogs');
    Route::get('/add-eventblog', [BigEventController::class, 'addEventBlog'])->name('addEventBlog');
    Route::post('/add-eventblog', [BigEventController::class, 'storeEventBlog'])->name('storeEventBlog');
    Route::get('/edit-eventblog/{id}', [BigEventController::class, 'editEventBlog'])->name('editEventBlog');
    Route::post('/edit-eventblog/{id}', [BigEventController::class, 'updateEventBlog'])->name('updateEventBlog');
    Route::get('/delete-confirmeventblog/{id}', [BigEventController::class, 'confirmDeleteEventBlog']);
    Route::get('/delete-eventblog/{id}', [BigEventController::class, 'deleteEventBlog']);

});


Route::prefix('/video')->group(function () {
    Route::get('/', [VideoController::class, 'getAllVideos'])->name('getAllVideos');
    Route::get('/add', [VideoController::class, 'addVideo'])->name('addVideo');
    Route::post('/add', [VideoController::class, 'saveVideo'])->name('saveVideo');
    Route::get('/edit/{id}', [VideoController::class, 'editVideo'])->name('editVideo');
    Route::post('/edit/{id}', [VideoController::class, 'updateVideo'])->name('updateVideo');
    Route::get('/{cat_name}/{name}', [VideoController::class, 'showVideo'])->name('showVideo');
    //NL1001:18Sep:2025:Added
    Route::delete('/videos/{id}', [VideoController::class, 'destroy'])->name('videos.destroy');

});
Route::prefix('/videofiles')->group(function () {
    Route::get('/', [VideoFileController::class, 'videosFile'])->name('videoFileList');
    Route::get('/add', [VideoFileController::class, 'videoAdd'])->name('videoAdd');
    Route::post('/add', [VideoFileController::class, 'addVideo'])->name('addVideoFiles');
    Route::get('/edit/{id}', [VideoFileController::class, 'editVideo'])->name('editVideoFiles');
    Route::post('/edit/{id}', [VideoFileController::class, 'videoEdit'])->name('videoEdit');
    Route::post('/upload', [VideoFileController::class, 'uploadVideo'])->name('uploadVideo');
    Route::get('/delete/{id}', [VideoFileController::class, 'del'])->name('deleteBoxVideo');
    Route::get('/del/{id}', [VideoFileController::class, 'deleteVideo'])->name('deleteVideo');
});
Route::prefix('/reel-videos')->group(function () {
    Route::get('/', [ReelVideoController::class, 'reelVideosFile'])->name('reelFileList');
    Route::get('/add', [ReelVideoController::class, 'reelVideoAdd'])->name('reelVideoAdd');
    Route::post('/add', [ReelVideoController::class, 'addReelVideo'])->name('addReelVideo');
    Route::get('/edit/{id}', [ReelVideoController::class, 'editReelVideo'])->name('editReelVideo');
    Route::post('/edit/{id}', [ReelVideoController::class, 'reelVideoEdit'])->name('reelVideoEdit');
    Route::post('/upload', [ReelVideoController::class, 'uploadReelVideo'])->name('uploadReelVideo');
    Route::get('/delete/{id}', [ReelVideoController::class, 'del'])->name('deleteBoxReelVideo');
    Route::get('/del/{id}', [ReelVideoController::class, 'deleteReelVideo'])->name('deleteReelVideo');

    Route::get('/sequence', [ReelVideoController::class, 'reelVideoSequence'])->name('reelVideoSequence');
    Route::post('/sequence', [ReelVideoController::class, 'updateReelVideoSequence'])->name('updateReelVideoSequence');
    Route::post('/sequence/reset/{id}', [ReelVideoController::class, 'resetReelVideoSequence'])->name('resetReelVideoSequence');
});
Route::prefix('/short-videos')->group(function () {
    Route::get('/load-more', [ReelVideoController::class, 'shortVideoLoadMore']);

    Route::get('/', [ReelVideoController::class, 'getAllShortVideos'])->name('getAllShortVideos');
    Route::get('/{cat_name}', [ReelVideoController::class, 'getShortVideosByCat'])->name('getShortVideosByCat');
    Route::get('/{cat_name}/{name}', [ReelVideoController::class, 'showVideo'])->name('showShortVideo');
});
Route::prefix('/webstory')->group(function () {
    Route::get('/', [WebStoryController::class, 'webStory'])->name('webStoryList');
    Route::get('/add', [WebStoryController::class, 'webStoryDetailsAdd'])->name('webStoryDetailsAdd');
    Route::post('/add', [WebStoryController::class, 'addWebStoryDetails'])->name('addWebStoryDetails');
    Route::get('/edit/{id}', [WebStoryController::class, 'editWebStory'])->name('editWebStory');
    Route::post('/edit/{id}', [WebStoryController::class, 'webStoryEdit'])->name('webStoryEdit');
    Route::post('/upload', [WebStoryController::class, 'uploadWebStory'])->name('uploadWebStory');
    Route::get('/delete/{id}', [WebStoryController::class, 'deleteWebStory'])->name('deleteWebStory');
    Route::get('/webstory-files/add', [WebStoryFileController::class, 'webStoryFileAdd'])->name('webStoryFileAdd');
    Route::post('/webstory-files/add', [WebStoryFileController::class, 'addWebStoryFile'])->name('addWebStoryFile');
    Route::get('/webstory-files/{id}', [WebStoryFileController::class, 'webStoriesFile'])->name('webStoriesFileList');
    Route::get('/webstory-files/edit/{id}', [WebStoryFileController::class, 'editWebStoryFile'])->name('editWebStoryFile');
    Route::post('/webstory-files/edit/{id}', [WebStoryFileController::class, 'webStoryFileEdit'])->name('webStoryFileEdit');
    Route::post('/webstory-files/upload', [WebStoryFileController::class, 'uploadWebStoryFile'])->name('uploadWebStoryFile');
    Route::get('/webstory-files/delete/{id}', [WebStoryFileController::class, 'deleteWebStoryFile'])->name('deleteWebStoryFile');
    Route::get('/sequence', [WebStoryController::class, 'webstorySequence'])->name('webstorySequence');
    Route::post('/sequence', [WebStoryController::class, 'updatewebstorySequence'])->name('updatewebstorySequence.update');
    Route::get('/file-sequence', [WebStoryFileController::class, 'allwebStoryList'])->name('allwebStoryList');
    Route::get('/file-sequence/{id}', [WebStoryFileController::class, 'getwebstoryFilesById'])->name('getwebstoryFileById');
    Route::post('/file-sequence-update', [WebStoryFileController::class, 'updatewebstoryFileSequence'])->name('updatewebstoryFileSequence');
    Route::post('/update-status', [WebStoryController::class, 'updateStatus'])->name('updateWebStoryStatus');
    Route::post('/publish/{id}', [WebStoryController::class, 'publish'])->name('webstory.publish');
    Route::get('/top-webstory', [WebStoryController::class, 'topWebStorySequence'])->name('topWebStorySequence');
    Route::post('/top-webstory-update', [WebStoryController::class, 'topWebSeqUpdate'])->name('topWebSeqUpdate');
    Route::post('/showWebStoryInTopUpdate', [WebStoryController::class, 'WebStoryInTopUpdate'])->name('WebStoryInTopUpdate');
});
Route::prefix('/posts')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('postList');
    Route::get('/archive', [BlogController::class, 'archivelist'])->name('postArchiveList');
    Route::get('/add', [BlogController::class, 'addBlog'])->name('addBlog');
    Route::post('/add', [BlogController::class, 'blogAdd'])->name('blogAdd');
    Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('editBlog');
    Route::post('/edit/{id}', [BlogController::class, 'editSave'])->name('blogEdit');
    Route::get('/delete/{id}', [BlogController::class, 'del'])->name('deleteBoxBlog');
    Route::get('/del/{id}', [BlogController::class, 'deleteBlog'])->name('delBlog');
    Route::get('/deletelive/{id}', [BlogController::class, 'deleteLiveUpdates'])->name('deleteLiveUpdates');
    Route::get('/status/{id}/{status}', [BlogController::class, 'statusBlog'])->name('statusBlog');
    Route::get('/breaking', [BlogController::class, 'breaking'])->name('braking');
    Route::post('/breaking', [BlogController::class, 'changeStatus'])->name('changeStatus');
    Route::get('/topnewsequence', [TopNewsController::class, 'index'])->name('menusequence');
    Route::post('/topnewsequence', [TopNewsController::class, 'updateTopNewsOrder'])->name('topnewsequence.update');
    Route::post('/topnewsequence/reset/{type}/{id}', [TopNewsController::class, 'resetTopNewsOrder'])->name('topnewsequence.reset');
    Route::get('/breakingList', [BlogController::class, 'breakingList'])->name('breakingList');
    Route::get('/addBreakingArticle', [BlogController::class, 'addBreakingArticle'])->name('addBreakingArticle');
    Route::post('/breakingArticleAdd', [BlogController::class, 'breakingArticleAdd'])->name('breakingArticleAdd');
    Route::get('/draft', [BlogController::class, 'draftList'])->name('draftList');
    Route::get('/unpublished', [BlogController::class, 'unpublishedList'])->name('unpublishedList');
    Route::get('/scheduled', [BlogController::class, 'scheduledList'])->name('scheduledList');
    Route::get('/podcast', [BlogController::class, 'podcastList'])->name('podcastList');
    Route::post('/update-breaking-status', [BlogController::class, 'updateBreakingStatus'])->name('changeBreakingStatus');
    Route::get('/trendingtopictag', [TrendingTopicTagController::class, 'index'])->name('trendingtopictag');
    Route::get('/trendingtopictag/add', [TrendingTopicTagController::class, 'addTrendingTag'])->name('addTrendingTag');
    Route::post('/trendingtopictag/add', [TrendingTopicTagController::class, 'saveTrendingTag'])->name('saveTrendingTag');
    Route::get('/trendingtopictag/edit/{id}', [TrendingTopicTagController::class, 'editTrendingTag'])->name('editTrendingTag');
    Route::post('/trendingtopictag/edit/{id}', [TrendingTopicTagController::class, 'trendingTagEdit'])->name('trendingTagEdit');
    Route::post('/trendingtopictag/update-status', [TrendingTopicTagController::class, 'updateStatus'])->name('updateStatus');
    Route::post('/trendingtopictag/trending-tag-sequence-update', [TrendingTopicTagController::class, 'updateTrendingTagSequence'])->name('updateTrendingTagSequence');
    Route::get('/live', [BlogController::class, 'allLiveBlogs'])->name('blogs.live');
    Route::get('/livelists/{id}', [BlogController::class, 'liveUpdates'])->name('LiveBlogs');
    Route::get('/addlive', [BlogController::class, 'addLiveUpdates'])->name('addLiveUpdates');
    Route::post('/addlive', [BlogController::class, 'liveUpdatesAdd'])->name('liveUpdatesAdd');
    Route::get('/editlive/{id}', [BlogController::class, 'editLiveUpdates'])->name('editLiveUpdates');
    Route::post('/liveUpdatesEdit/{id}', [BlogController::class, 'liveUpdatesEdit'])->name('liveUpdatesEdit');
});
Route::prefix('/categories')->group(function () {
    Route::get('/{name}/load-more', [CategoryController::class, 'categoryLoadMore']);

    Route::get('/', [CategoryController::class, 'index'])->name('categoryList');
    Route::get('/add', [CategoryController::class, 'addCategory'])->name('addCategory');
    Route::post('/add', [CategoryController::class, 'categoryAdd'])->name('categoryAdd');
    Route::get('/edit/{id}', [CategoryController::class, 'editCategory'])->name('editCategory');
    Route::post('/edit/{id}', [CategoryController::class, 'categoryEdit'])->name('categoryEdit');
    Route::get('/delete/{id}', [CategoryController::class, 'del'])->name('deleteBoxCategory');
    Route::get('/del/{id}', [CategoryController::class, 'deleteCategory'])->name('delCategory');
    Route::post('/update-status', [CategoryController::class, 'updateStatus'])->name('updateCategoryStatus');

});
Route::prefix('/state')->group(function () {
    Route::get('/{name}/load-more', [StateController::class, 'stateLoadMore']);

    Route::get('/', [StateController::class, 'index'])->name('stateList');
    Route::get('/add', [StateController::class, 'add'])->name('addState');
    Route::post('/add', [StateController::class, 'save'])->name('stateAdd');
    Route::get('/edit/{id}', [StateController::class, 'edit'])->name('editState');
    Route::post('/edit/{id}', [StateController::class, 'editSave'])->name('stateEdit');
    Route::get('/delete/{id}', [StateController::class, 'del'])->name('deleteBoxState');
    Route::get('/del/{id}', [StateController::class, 'deleteState'])->name('delState');
    Route::post('/update-status', [StateController::class, 'updateStatus'])->name('updateStateStatus');
});
Route::prefix('/district')->group(function () {
    Route::get('/', [DistrictController::class, 'index'])->name('districtList');
    Route::get('/add', [DistrictController::class, 'add'])->name('addDistricte');
    Route::post('/add', [DistrictController::class, 'save'])->name('districtAdd');
    Route::get('/edit/{id}', [DistrictController::class, 'edit'])->name('editDistrict');
    Route::post('/edit/{id}', [DistrictController::class, 'editSave'])->name('districtEdit');
    Route::get('/delete/{id}', [DistrictController::class, 'del'])->name('deleteBoxDistrict');
    Route::get('/del/{id}', [DistrictController::class, 'deleteDistrict'])->name('delDistrict');
    Route::post('/update-status', [DistrictController::class, 'updateStatus'])->name('updateDistrictStatus');
});

Route::prefix('/election')->group(function () {
    Route::get('/add', [ElectionResultController::class, 'add'])->name('addElection');
    Route::post('/save', [ElectionResultController::class, 'save'])->name('saveElectionData');
    Route::get('/results', [ElectionResultController::class, 'showresults'])->name('showElectionResults');
 
    // Edit/update/delete
    Route::get('/edit/{id}', [ElectionResultController::class, 'edit'])->name('editElection');
    Route::post('/update/{id}', [ElectionResultController::class, 'update'])->name('updateElection');
    Route::get('/delete/{id}', [ElectionResultController::class, 'delete'])->name('deleteElection');
 
    // Party routes
    // Party Routes
    Route::get('/add-party', [PartyController::class, 'add'])->name('addParty');
    Route::post('/save-party', [PartyController::class, 'save'])->name('saveParty');
    Route::get('/party/list', [PartyController::class, 'list'])->name('party.list');
    Route::get('/party/edit/{id}', [PartyController::class, 'edit'])->name('party.edit');
    Route::put('/party/update/{id}', [PartyController::class, 'update'])->name('party.update');
    Route::post('/party/update-status/{id}', [PartyController::class, 'updateStatus'])->name('party.updateStatus');
    Route::delete('/party/delete/{id}', [PartyController::class, 'destroy'])->name('party.destroy');
    // Candidate Routes
    Route::get('/candidates', [CandidateController::class, 'list'])->name('candidates.list'); // show all candidates
    Route::get('/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');  // Party routes
    Route::post('/candidates/store', [CandidateController::class, 'store'])->name('candidates.store');
    Route::get('/candidates/edit/{id}', [CandidateController::class, 'edit'])->name('candidates.edit');
    Route::post('/candidates/update/{id}', [CandidateController::class, 'update'])->name('candidates.update');
    Route::post('/candidates/update-status/{id}', [CandidateController::class, 'updateStatus'])->name('candidates.updateStatus');
    Route::delete('/candidate/delete/{id}', [CandidateController::class, 'destroy'])->name('candidate.destroy');

    // Mahamukabla Routes
    Route::get('/mahamukabla/create', [MahamukablaController::class, 'create'])->name('mahamukabla.create');
    Route::post('/mahamukabla/store', [MahamukablaController::class, 'store'])->name('mahamukabla.store');
    Route::get('/mahamukabla/show', [MahamukablaController::class, 'show'])->name('mahamukabla.show');  // Party routes
     Route::patch('/mahamukabla/toggle/{id}', [MahamukablaController::class, 'toggle'])->name('mahamukabla.toggle');
    Route::post('/mahamukabla/update-status', [MahamukablaController::class, 'updateSlideStatus'])->name('mahamukabla.updateStatus');
    Route::delete('/mahamukabla/{id}', [MahamukablaController::class, 'destroy'])->name('mahamukabla.destroy');

    Route::get('/manage-vote-count', [ElectionResultController::class, 'manageVoteCount'])->name('voteCount');
    Route::post('/manage-vote-count/save', [ElectionResultController::class, 'saveVoteCount'])->name('voteSave');
    Route::get('/exit-poll', [ElectionResultController::class, 'exitpoll'])->name('exitpoll');
    Route::post('/exit-poll/save', [ElectionResultController::class, 'exitpollsave'])->name('exitpollsave');

    Route::get('/manage-top-party-seats', [ElectionResultController::class, 'manageSeats'])->name('manageSeats');
    Route::post('/manage-top-party-seats/save', [ElectionResultController::class, 'saveTopSeats'])->name('saveTopSeats');
    
    Route::post('/candidates/{id}/status', [CandidateController::class, 'updateCandidateStatus'])->name('candidates.updateCandidateStatus');

});
Route::post('/toggle-maha-section', [HomeController::class, 'toggleMahaSection'])->name('toggle.maha.section');
Route::post('/toggle-live-section', [HomeController::class, 'toggleLiveSection'])->name('toggle.live.section');
Route::post('/toggle-exit-poll', [HomeController::class, 'toggleExitPoll'])->name('toggle.exit.poll');
Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('RoleList');
    Route::get('/add', [RoleController::class, 'add'])->name('addRole');
    Route::post('/add', [RoleController::class, 'save'])->name('roleAdd');
    Route::get('edit/{id}', [RoleController::class, 'edit'])->name('editRole');
    Route::post('edit/{id}', [RoleController::class, 'editSave'])->name('editSave');
});
Route::prefix('users')->group(function () {
    // Route::get('/', [UsersController::class, 'index'])->name('UserList');
    // Route::get('/add', [UsersController::class, 'add'])->name('addUser');
    // Route::post('/add', [UsersController::class, 'save'])->name('userAdd');
    // Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('editUser');
    // Route::post('/edit/{id}', [UsersController::class, 'editSave'])->name('userSave');
    Route::get('/change-password', [UsersController::class, 'changePassword'])->name('changePassword');
    Route::post('/change-password/{id}', [UsersController::class, 'savePassword'])->name('savePassword');
});
Route::prefix('rashiphal')->group(function () {
    Route::get('/', [RashiphalController::class, 'index'])->name('rashiphalList');
    Route::get('/edit/{id}', [RashiphalController::class, 'edit'])->name('editRashiphal');
    Route::post('/edit/{id}', [RashiphalController::class, 'editSave'])->name('rashiphalSave');
});
Route::prefix('alluserslist')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('UserList');
    // Route::get('/allusers-change-password/{id}', [SuperAdminController::class, 'changePassword'])->name('changePassword');
    // Route::post('/allusers-change-password/{id}', [SuperAdminController::class, 'savePassword'])->name('savePassword');
    Route::get('/add', [SuperAdminController::class, 'add'])->name('addUser');
    Route::post('/add', [SuperAdminController::class, 'save'])->name('userAdd');
    Route::get('/edit/{id}', [SuperAdminController::class, 'edit'])->name('editUser');
    Route::post('/edit/{id}', [SuperAdminController::class, 'editSave'])->name('userSave');
    Route::get('/delete/{id}', [SuperAdminController::class, 'del'])->name('deleteBoxUser');
    Route::get('/del/{id}', [SuperAdminController::class, 'deleteUser'])->name('deleteUser');
    Route::get('/allusers-reset-password/{id}', [SuperAdminController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/allusers-reset-password/{id}', [SuperAdminController::class, 'saveResetPassword'])->name('saveResetPassword');
    Route::post('/update-status', [SuperAdminController::class, 'updateUserStatus'])->name('updateUserStatus');
});
Route::prefix('menu')->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('menulist');
    Route::get('/all', [MenuController::class, 'allMenus'])->name('allMenus');
    Route::get('/add', [MenuController::class, 'addMenu'])->name('addMenu');
    Route::post('/add', [MenuController::class, 'menuAdd'])->name('menuAdd');
    Route::get('/edit/{id}', [MenuController::class, 'editmenu'])->name('editmenu');
    Route::post('/edit/{id}', [MenuController::class, 'menuedit'])->name('menuedit');
    Route::get('/delete/{id}', [MenuController::class, 'del'])->name('deleteBoxMenu');
    Route::get('/del/{id}', [MenuController::class, 'deleteMenu'])->name('delMenu');
    Route::post('/update-menu-status', [MenuController::class, 'updateMenuStatus'])->name('updateMenuStatus');
});
Route::prefix('home-category')->group(function () {
    Route::get('/', [HomeCategorySequence::class, 'getAllCategoryList'])->name('homeCategoryList');
    Route::get('/sequence', [HomeCategorySequence::class, 'index'])->name('categorysequence');
    Route::post('/sequence', [HomeCategorySequence::class, 'updateCategorySequence'])->name('categorysequence.update');
    Route::get('/add', [HomeCategorySequence::class, 'addCat'])->name('addHomeCategory');
    Route::post('/add', [HomeCategorySequence::class, 'saveCat'])->name('homeCategoryAdd');
    Route::get('/edit/{id}', [HomeCategorySequence::class, 'editCat'])->name('editHomeCategory');
    Route::post('/edit/{id}', [HomeCategorySequence::class, 'editSave'])->name('homeCategorySave');
    Route::get('/delete/{id}', [HomeCategorySequence::class, 'deleteCat'])->name('delHomeCategory');
    Route::post('/update-active-status', [HomeCategorySequence::class, 'updateActiveStatus'])->name('updateActiveStatus');
});
Route::prefix('vote')->group(function () {
    Route::get('/', [VoteController::class, 'show'])->name('votelist');
    Route::get('/add', [VoteController::class, 'addVote'])->name('addVote');
    Route::post('/add', [VoteController::class, 'saveVote'])->name('saveVote');
    Route::get('/edit/{id}', [VoteController::class, 'editVote'])->name('editVote');
    Route::post('/edit/{id}', [VoteController::class, 'voteEdit'])->name('voteEdit');
    Route::get('/vote-option/add', [VoteOptionController::class, 'addVoteOption'])->name('addVoteOption');
    Route::post('/vote-option/add', [VoteOptionController::class, 'saveVoteOption'])->name('saveVoteOption');
    Route::get('/vote-option/{id}', [VoteOptionController::class, 'showVoteOption'])->name('voteOptionList');
    Route::get('/vote-option/edit/{id}', [VoteOptionController::class, 'editVoteOption'])->name('editVoteOption');
    Route::post('/vote-option/edit/{id}', [VoteOptionController::class, 'voteOptionEdit'])->name('voteOptionEdit');
});
Route::prefix('/ads')->group(function () {
    Route::get('/', [AdsController::class, 'index'])->name('getAllAds');
    Route::get('/add', [AdsController::class, 'addAds'])->name('addAds');
    Route::post('/add', [AdsController::class, 'storeAd'])->name('storeAd');
    Route::get('/edit/{id}', [AdsController::class, 'edit'])->name('edit');
    Route::post('/edit/{id}', [AdsController::class, 'updateAd'])->name('updateAd');
});
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/news-sitemap.xml', [SitemapController::class, 'newsSitemap']);
Route::get('/webstories-sitemap.xml', [SitemapController::class, 'webstoriesSitemap']);
Route::get('/articles-sitemap.xml', [SitemapController::class, 'sitemapIndex']);
Route::get('/sitemap/generic-articles-{date}.xml', [SitemapController::class, 'dailySitemap'])
     ->where('date', '\d{4}-\d{2}-\d{2}');
Route::get('/videos-sitemap.xml', [SitemapController::class, 'videoSitemap']);
Route::get('/reels-sitemap.xml', [SitemapController::class, 'reelVideoSitemap']);


Route::get('/feed', [RSSFeedController::class, 'index']);
Route::get('/home-videos', [VideoController::class, 'HomepageVideo'])->name('HomepageVideo');
Route::get('/menusequence', [MenuSequenceController::class, 'index'])->name('getmenusequence');
Route::post('/menusequence', [MenuSequenceController::class, 'updateMenuOrder'])->name('menusequence.update');
Route::get('/statesequence', [StateSequenceController::class, 'index'])->name('statesequence');
Route::post('/statesequence', [StateSequenceController::class, 'updateStateOrder'])->name('statesequence.update');
Route::post('/update-default-state', [StateSequenceController::class, 'updateDefaultState'])->name('defaultState.update');
Route::get('/bannersequence', [BannerHomePageController::class, 'index'])->name('bannersequence');
Route::post('/bannersequence', [BannerHomePageController::class, 'updateBannerDisplayMode'])->name('bannersequence.update');
Route::post('/bannersequence/bannerUpdate', [BannerHomePageController::class, 'bannerUpdate'])->name('bannerUpdate');
Route::get('author/{name}', [StoryController::class, 'author'])->name('author');
Route::get('state/{name}', [StoryController::class, 'state'])->name('state');
Route::get('/live/{cat_name}/{name}/amp', [StoryController::class, 'liveBlogsAmp'])->name('liveBlogsAmp');
Route::get('/live/{cat_name}/{name}', [StoryController::class, 'liveBlogs'])->name('liveBlogs');
Route::get('/about', [StoryController::class, 'about'])->name('about');
Route::get('/search', [StoryController::class, 'search'])->name('search');
Route::get('/privacy', [StoryController::class, 'privacy'])->name('privacy');
Route::get('/disclaimer', [StoryController::class, 'disclaimer'])->name('disclaimer');
Route::get('/contact', [StoryController::class, 'contact'])->name('contact');
Route::get('/breakingnews/{slug}', [StoryController::class, 'breakingNews'])->name('breakingNews');
Route::get('/videos', [StoryController::class, 'videos'])->name('videos');
Route::get('/videos/{cat_name}', [StoryController::class, 'videosCategory'])->name('videosCategory');
Route::get('/nmfvideos', [StoryController::class, 'nmfvideos'])->name('nmfvideos');
Route::get('/nmfvideos/{cat_name}', [StoryController::class, 'nmfvideosCategory'])->name('nmfvideosCategory');

Route::get('/event/video/{cat_name}/{name}', [BigEventController::class, 'showEventVideo'])->name('showEventVideo');
Route::get('/event/{cat_name}/{name}', [BigEventController::class, 'showEventStory'])->name('showEventStory');

Route::get('/photos', [ElectionResultController::class, 'index'])->name('index');
Route::get('/category', [ElectionResultController::class, 'category'])->name('category');

Route::get('/web-stories', [StoryController::class, 'getAllwebStories'])->name('getAllwebStories');
Route::get('/web-stories/{cat_name}', [StoryController::class, 'webStoriesByCategory'])->name('webStoriesByCategory');
Route::get('/web-stories/{cat_name}/{name}', [StoryController::class, 'webStoryDetail'])->name('web-stories');
Route::get('/{cat_name}/{name}/amp', [StoryController::class, 'showStoryAmp'])->name('showStoryAmp');
Route::get('/{cat_name}/{name}', [StoryController::class, 'showStory'])->name('showStory');
Route::get('/{cat_name}', [StoryController::class, 'category'])->name('category');
Route::post('/', [HomeController::class, 'handlePost']);
Route::post('/increase-webhitcount', [StoryController::class, 'increaseWebHitCount']);
Route::post('/submit-vote/{id}', [HomeController::class, 'savedVote'])->name('vote.submit');
Route::get('/api/vote/results/{id}', [HomeController::class, 'getVoteResults']);


