<div class="filter-content">
    <button class="filter-submit-button" form="filter-form" type="submit">Filter</button>
    <strong>{{strtoupper($filterTitle)}}</strong>
    <div>
        <strong>Status</strong>
        <div>
            <div>
                <div>
                    <input form="filter-form" type="radio" name="sort-publish" value="2" @if($published==2) checked @endif>
                    <label for="">All</label>
                </div>
                <div>
                    <input form="filter-form" type="radio" name="sort-publish" value="1" @if($published==1) checked @endif>
                    <label for="">Published</label>
                </div>
                <div>
                    <input form="filter-form" type="radio" name="sort-publish" value="0" @if($published==0) checked @endif>
                    <label for="">Not Published</label>
                </div>
            </div>
        </div>
        <div>
            <strong>Subjects</strong>
            <div>
                @foreach($subjects as $subject)
                <div>
                    <input form="filter-form" type="checkbox" name="{{$subject->subjectID . 'subject'}}" @if(in_array($subject->subjectID, $filterSubjects)) checked @endif>
                    <label for="">{{$subject->subjectName}}</label>
                </div>
                @endforeach
            </div>
        </div>
        <div>
            <strong>Sort By</strong>
            <div>
                <div>
                    <input form="filter-form" type="radio" name="sort-date" value="desc" @if($sortDate=='desc' ) checked @endif>
                    <label for="">Latest</label>
                </div>
                <div>
                    <input form="filter-form" type="radio" name="sort-date" value="asc" @if($sortDate=='asc' ) checked @endif>
                    <label for="">Oldest</label>
                </div>
            </div>
        </div>
    </div>
</div>