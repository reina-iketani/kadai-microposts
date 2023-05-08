
@if (isset($favoritings))
    <ul class="list-none">
        @foreach ($favoritings as $favoriting)
            <li class="flex items-start gap-x-2 mb-4">
                {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                <div class="avatar">
                    <div class="w-12 rounded">
                        <img src="{{ Gravatar::get($favoriting->user->email) }}" alt="" />
                    </div>
                </div>
                <div>
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        <a class="link link-hover text-info" href="{{ route('users.show', $favoriting->user->id) }}">{{ $favoriting->user->name }}</a>
                        <span class="text-muted text-gray-500">posted at {{ $favoriting->created_at }}</span>
                    </div>
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($favoriting->content)) !!}</p>
                    </div>
                    @include('users.favorite_button')
                    <div>
                    
                        @if (Auth::id() == $favoriting->user_id)
                            {{-- 投稿削除ボタンのフォーム --}}
                            <form method="POST" action="{{ route('microposts.destroy', $favoriting->id) }}">
                                

                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm normal-case" 
                                    onclick="return confirm('Delete id = {{ $favoriting->id }} ?')">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $favoritings->links() }}
@endif