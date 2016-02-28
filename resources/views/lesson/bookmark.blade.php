<div role="tabpanel" class="tab-pane" id="bookmarks">
    <table class="table table-bordered table-responsive">
        <thead>
            <tr>
                <th class="col-md-4">Date Time</th>
                <th class="col-md-8">Bookmark</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookmarks as $bookmark)
                <tr>

                    <td>
                        <a href="#" class="bookmarked_at" data-name="bookmarked_at" data-type="text"
                           data-pk="{{$bookmark->id}}" data-url="/bookmark/{{$bookmark->id}}"
                           data-title="YYYY-mm-dd hh:ii:ss">{{$bookmark->bookmarked_at->timezone(auth()->user()->timezone)}}</a>
                    </td>
                    <td>
                        <a href="#" class="bookmark" data-name="bookmark" data-type="text" data-pk="{{$bookmark->id}}"
                           data-url="/bookmark/{{$bookmark->id}}"
                           data-title="bookmark">{{$bookmark->bookmark}}</a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td><input class="form-control" placeholder="datetime" id="new_bookmarked_at"></td>
                <td>
                    <div class="input-group">
                        <input class="form-control" placeholder="bookmark" id="new_bookmark">
                        <span class="input-group-btn">
                           <button id="save-new-bookmark" class="btn btn-primary" type="button">Save New Bookmark</button>
                        </span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>