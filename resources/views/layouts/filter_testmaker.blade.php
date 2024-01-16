<div class="filter-content">
    <button class="filter-submit-button" form="filter-form" type="submit">Filter</button>
    <strong>{{strtoupper($filterTitle)}}</strong>
    <div>
        <div>
            <strong>Sort By</strong>
            <div>
                <div>
                    <input form="filter-form" type="radio" name="sort-date" value="desc" @if($sortDate == 'desc') checked @endif>
                    <label for="">Latest</label>
                </div>
                <div>
                    <input form="filter-form" type="radio" name="sort-date" value="asc" @if($sortDate == 'asc') checked @endif>
                    <label for="">Oldest</label>
                </div>
            </div>
        </div>
    </div>
</div>