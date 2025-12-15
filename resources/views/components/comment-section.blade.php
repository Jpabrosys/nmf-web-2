<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="cmmt-container">
    <div class="cmmt-header">
        <div class="cmmt-title">
            टिप्पणियाँ <span>{{ $comments->total() }}</span>
        </div>
        {{-- <button class="close_btn" id="close-btn2" type="button" aria-label="Close">
            <i class="fa-solid fa-times"></i> 
        </button> --}}
    </div>
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="cmmt-success">
            <div class="success__icon">
                <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                        d="m12 1c-6.075 0-11 4.925-11 11s4.925 11 11 11 11-4.925 11-11-4.925-11-11-11zm4.768 9.14c.0878-.1004.1546-.21726.1966-.34383.0419-.12657.0581-.26026.0477-.39319-.0105-.13293-.0475-.26242-.1087-.38085-.0613-.11844-.1456-.22342-.2481-.30879-.1024-.08536-.2209-.14938-.3484-.18828s-.2616-.0519-.3942-.03823c-.1327.01366-.2612.05372-.3782.1178-.1169.06409-.2198.15091-.3027.25537l-4.3 5.159-2.225-2.226c-.1886-.1822-.4412-.283-.7034-.2807s-.51301.1075-.69842.2929-.29058.4362-.29285.6984c-.00228.2622.09851.5148.28067.7034l3 3c.0983.0982.2159.1748.3454.2251.1295.0502.2681.0729.4069.0665.1387-.0063.2747-.0414.3991-.1032.1244-.0617.2347-.1487.3236-.2554z"
                        fill="#393a37" fill-rule="evenodd"></path>
                </svg>
            </div>
            <div class="cmm-alert">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="cmmt-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="cmmt">
        {{-- Comment Form --}}
        @if ($isLoggedIn)
            <div class="cmmt-box">
                <div class="cmmt-row">
                    <div class="cmmt-avatar">
                        {{ substr($currentViewer->name, 0, 1) }}
                    </div>
                    <span style="font-weight:600; font-size:1.15rem;">{{ $currentViewer->name }}</span>
                </div>
                {{-- <form class="cmmt-form" wire:submit.prevent="postComment">
                    <textarea class="commt-area" wire:model="body" rows="4" placeholder="Write your comment in style..."></textarea>
                    @error('body')
                        <span style="color:#f87171;font-size:15px;margin-top:5px;display:block;">{{ $message }}</span>
                    @enderror
                    <button class="post-btn" type="submit">
                        Post Comment
                    </button>
                </form> --}}
                <form id="comment-form" class="cmmt-form">
                    @csrf
                    <textarea class="commt-area" name="body" rows="4" placeholder="Write your comment in style..."></textarea>
                    @error('body')
                        <span style="color:#f87171;font-size:15px;margin-top:5px;display:block;">{{ $message }}</span>
                    @enderror
                    <div id="comment-error" style="color:#f87171;font-size:15px;margin-top:5px;display:none;"></div>
                    {{-- pass model details as hidden fields --}}
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    <input type="hidden" name="model_type" value="{{ get_class($model) }}">

                    <button class="post-btn" type="submit">
                        Post Comment
                    </button>
                </form>

            </div>
        @else
            <div class="google-login-box">
                <p> कृपया <span style="color:#e53935;font-weight:bold;">Google से लॉग इन करें</span> टिप्पणी पोस्ट करने
                    के
                    लिए</p>

                <a href="{{ route('viewer.google.redirect') }}" class="google-login-btn">
                    <svg style="width:26px;height:26px;margin-right:11px;" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    Google से लॉग इन करें
                </a>
            </div>
        @endif
    </div>

    {{-- Comments List --}}
    <div id="comment-list" class="cmmt-list">
        @foreach ($comments as $comment)
            <div class="cmmt-item">
                {{-- Comment --}}
                <div class="cmmt-item-box">
                    <div class="cmmt-item-header">
                        <div class="cmmt-row">
                            <div class="cmmt-avatar2">
                                {{ substr($comment->viewer->name, 0, 1) }}
                            </div>
                            <span style="color:#242424;font-size:15px;">{{ $comment->viewer->name }}</span>
                        </div>
                        <span style="margin-left:18px;color:#7c7c7c;font-size:13px;">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p style="color:#383838;margin-bottom:12px;line-height:1.7;">{{ $comment->body }}</p>
                    <div style="display:flex;align-items:center;gap:42px;">
                        @if ($isLoggedIn)
                            <button class="like-btn" data-id="{{ $comment->id }}"
                                style="display:inline-flex;align-items:center;gap:6px;
                                color:{{ $comment->isLikedByViewer($currentViewer->id) ? '#e53935' : '#ccc' }};
                                font-weight:700;font-size:16px; border:none; background:none; cursor:pointer; transition:.25s;">
                                <svg style="width:22px;height:22px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                </svg>
                                <span>{{ $comment->likes_count }}</span>
                            </button>
                            <button class="reply-btn" data-id="{{ $comment->id }}"
                                style="color:#e53935;font-weight:600;text-transform:uppercase; font-size:15px; transition:.2s; border:none;background:none;cursor:pointer;">Reply</button>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:5px;color:#bbb;font-size:15px;">
                                <svg style="width:22px;height:22px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                </svg>
                                <span>{{ $comment->likes_count }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Reply Form --}}
                {{-- @if ($replyTo === $comment->id) --}}
                    {{-- <div
                        style="    margin-top: 1.2rem;
                                    margin-left: 2rem;
                                    padding: 1.2rem;
                                    background: #ffffff;
                                    border-radius: 10px;
                                    border: 1.5px solid #c6c6c6;">
                        <form wire:submit.prevent="postReply">
                            <textarea wire:model="replyBody" rows="3" style="padding: 11px; background: #fff; color:#333; border: 1.5px
                                solid #d5d5d5; border-radius: 7px; font-size: 1rem; resize: vertical; box-sizing: border-box;">
                    </textarea>
                            @error('replyBody')
                                <span
                                    style="color:#ffd6d5;font-size:14px;display:block;margin-top:4px;">{{ $message }}</span>
                            @enderror
                            <div style="margin-top:0.6rem;display:flex;gap:12px;">
                                <button type="submit"
                                    style="padding:10px 26px;background:#e53935;color:#fff;font-weight:700;border-radius:6px;border:none;font-size:1.02rem;cursor:pointer;box-shadow:0 1px 8px #e5393533;">Reply</button>
                                <button type="button" wire:click="cancelReply"
                                    style="padding:10px 23px;background:#271718;color:#fff;border-radius:6px;border:1.5px solid #1a1022;font-size:1.02rem;cursor:pointer;">Cancel</button>
                            </div>
                        </form>
                    </div> --}}
                {{-- @endif --}}

                {{-- Replies --}}
                @if ($comment->replies->count() > 0)
                    <div class="mt-3 ms-4">
                        @foreach ($comment->replies as $reply)
                            <div class="cmmt-item-box2 mb-2">
                                <div class="cmmt-item-header p-0">

                                    <div class="cmmt-row">
                                        <div class="cmmt-avatar2">
                                            {{ substr($reply->viewer->name, 0, 1) }}
                                        </div>
                                        <span class="usr-name">{{ $reply->viewer->name }}</span>
                                    </div>
                                    <span
                                        style="color:#bdbdbd;font-size:12px;margin-left:10px;">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p style="margin-bottom:3px; color:#333;">{{ $reply->body }}</p>
                                @if ($isLoggedIn)
                                    <div style="margin-top:5px;">
                                        <button class="like-btn" data-id="{{ $reply->id }}"
                                            style="display:inline-flex;align-items:center;gap:5px;color:{{ $reply->isLikedByViewer($currentViewer->id) ? '#e53935' : '#999' }};font-weight:700;font-size:14px;border:none;background:none;cursor:pointer;">
                                            <svg style="width:17px;height:17px;" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                            </svg>
                                            <span>{{ $reply->likes_count }}</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    {{-- Pagination --}}
    <div style="margin-top:2.3rem;">
        {{ $comments->links() }}
    </div>
</div>

</div>

<script>
    document.getElementById('comment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const body = formData.get('body').trim();
        if (!body) {
            alert('Please write a comment first!');
            return;
        }
        try {
            const response = await fetch("{{ route('comments.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();
            if (!response.ok) {
                document.getElementById('comment-error').style.display = 'block';
                document.getElementById('comment-error').innerText = result.message || 'Validation failed.';
            } else {
                document.getElementById('comment-error').style.display = 'none';
                // Add new comment dynamically
                const commentList = document.getElementById('comment-list');
                const div = document.createElement('div');
                div.classList.add('cmmt-item');
                div.innerHTML = `
                    <div class="cmmt-item-box">
                        <div class="cmmt-item-header">
                            <div class="cmmt-row">
                                <div class="cmmt-avatar2">${result.comment.viewer_initial}</div>
                                <span style="color:#242424;font-size:15px;">${result.comment.viewer_name}</span>
                            </div>
                            <span style="margin-left:18px;color:#7c7c7c;font-size:13px;">just now</span>
                        </div>
                        <p style="color:#383838;margin-bottom:12px;line-height:1.7;">${result.comment.body}</p>
                        <div style="display:flex;align-items:center;gap:42px;">
                            <button class="like-btn" data-id="${result.comment.id}"
                                style="display:inline-flex;align-items:center;gap:6px;color:#ccc;
                                font-weight:700;font-size:16px; border:none; background:none; cursor:pointer; transition:.25s;">
                                <svg style="width:22px;height:22px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                </svg>
                                <span>0</span>
                            </button>
                            <button class="reply-btn" data-id="${result.comment.id}"
                                style="color:#e53935;font-weight:600;text-transform:uppercase; font-size:15px;
                                transition:.2s; border:none;background:none;cursor:pointer;">
                                Reply
                            </button>
                        </div>
                    </div>
                `;

                commentList.prepend(div);

                form.reset(); // Clear textarea
            }
        } catch (err) {
            document.getElementById('comment-error').style.display = 'block';
            document.getElementById('comment-error').innerText = 'Something went wrong.';
        }
    });

</script>

<script>
document.addEventListener('click', function(e) {
    if (e.target.closest('.reply-btn')) {
        const btn = e.target.closest('.reply-btn');
        const commentId = btn.dataset.id;

        // Remove any existing reply forms
        document.querySelectorAll('.reply-form-container').forEach(f => f.remove());

        // Build styled reply form
        const formHtml = `
            <div class="reply-form-container" 
                style="margin-top:1.2rem;margin-left:2rem;padding:1.2rem;
                       background:#ffffff;border-radius:10px;border:1.5px solid #c6c6c6;">
                <form class="reply-form">
                    @csrf
                    <textarea name="replyBody" rows="3" required
                        style="padding:11px;background:#fff;color:#333;
                               border:1.5px solid #d5d5d5;border-radius:7px;
                               font-size:1rem;resize:vertical;box-sizing:border-box;width:100%;"></textarea>
                    <input type="hidden" name="parent_id" value="${commentId}">
                    <input type="hidden" name="model_id" value="{{ $model->id }}">
                    <div style="margin-top:0.6rem;display:flex;gap:12px;">
                        <button type="submit"
                            style="padding:10px 26px;background:#e53935;color:#fff;
                                   font-weight:700;border-radius:6px;border:none;
                                   font-size:1.02rem;cursor:pointer;
                                   box-shadow:0 1px 8px #e5393533;">
                            Reply
                        </button>
                        <button type="button" class="cancel-reply"
                            style="padding:10px 23px;background:#271718;color:#fff;
                                   border-radius:6px;border:1.5px solid #1a1022;
                                   font-size:1.02rem;cursor:pointer;">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>`;

        const commentBox = btn.closest('.cmmt-item-box');
        commentBox.insertAdjacentHTML('afterend', formHtml);
    }

    // Handle cancel button
    if (e.target.closest('.cancel-reply')) {
        e.target.closest('.reply-form-container').remove();
    }
});

document.addEventListener('submit', async function(e) {
    const form = e.target.closest('.reply-form');
    if (!form) return; 
    e.preventDefault(); 

    try {
        const formData = new FormData(form);

        const response = await fetch("{{ route('comments.reply') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();
        if (response.ok) {
            const replyHtml = `
                <div class="cmmt-item-box2 mb-2">
                    <div class="cmmt-item-header p-0">
                        <div class="cmmt-row">
                            <div class="cmmt-avatar2">${result.comment.viewer_initial}</div>
                            <span class="usr-name">${result.comment.viewer_name}</span>
                        </div>
                        <span style="color:#bdbdbd;font-size:12px;margin-left:10px;">just now</span>
                    </div>
                    <p style="margin-bottom:3px; color:#333;">${result.comment.body}</p>
                    <div style="margin-top:5px;">
                        <button class="like-btn" data-id="${result.comment.id}"
                            style="display:inline-flex;align-items:center;gap:5px;color:#999;font-weight:700;font-size:14px;border:none;background:none;cursor:pointer;">
                            <svg style="width:17px;height:17px;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                            </svg>
                            <span>${result.comment.likes_count}</span>
                        </button>
                    </div>
                </div>`;

            // Find parent comment container
            const parentItem = form.closest('.cmmt-item');
            let repliesContainer = parentItem.querySelector('.mt-3.ms-4');

            // Create replies container if missing
            if (!repliesContainer) {
                repliesContainer = document.createElement('div');
                repliesContainer.className = 'mt-3 ms-4';
                parentItem.appendChild(repliesContainer);
            }

            // Append new reply
            repliesContainer.insertAdjacentHTML('beforeend', replyHtml);

            // Remove entire reply form container (not just the form)
            const container = form.closest('.reply-form-container');
            if (container) container.remove();
        } else {
            alert(result.message || 'Validation failed.');
        }
    } catch (err) {
        console.error('Error submitting reply:', err);
        alert('Something went wrong. Please try again.');
    }
});

</script>

<script>
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.like-btn');
        if (!btn) return;

        const commentId = btn.dataset.id;
        console.log("Liking comment ID:", commentId);

        try {
            const response = await fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });

            const result = await response.json();

            if (response.ok) {
                const countEl = btn.querySelector('span');
                countEl.textContent = result.likes_count;
                btn.style.color = result.liked ? '#e53935' : '#ccc';
            } else {
                alert(result.message || 'Unable to like comment.');
            }
        } catch (err) {
            console.error('Error toggling like:', err);
            alert('Something went wrong. Please try again.');
        }
    });

</script>




