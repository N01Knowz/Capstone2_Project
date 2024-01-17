<div class="filter-content">
    <button class="filter-submit-button" form="filter-form" type="submit">Filter</button>
    <strong>{{strtoupper($page)}}</strong>
    <div>
        <strong>Filter</strong>
        <div>
            <strong>Test Type</strong>
            <div>
                <div>
                    <input form="filter-form" type="checkbox" name="mcq-filter" @if(in_array('mcq', $filterType)) checked @endif>
                    <label for="">MCQ</label>
                </div>
                <div>
                    <input form="filter-form" type="checkbox" name="tf-filter" @if(in_array('tf', $filterType)) checked @endif>
                    <label for="">TF</label>
                </div>
                <!-- <div>
                    <input form="filter-form" type="checkbox" name="">
                    <label for="">MTF</label>
                </div> -->
                <div>
                    <input form="filter-form" type="checkbox" name="matching-filter" @if(in_array('matching', $filterType)) checked @endif>
                    <label for="">Matching</label>
                </div>
                <div>
                    <input form="filter-form" type="checkbox" name="enumeration-filter" @if(in_array('enumeration', $filterType)) checked @endif>
                    <label for="">Enumeration</label>
                </div>
                <div>
                    <input form="filter-form" type="checkbox" name="mixed-filter" @if(in_array('mixed', $filterType)) checked @endif>
                    <label for="">Mixed Test</label>
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