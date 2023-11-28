@extends('layouts.student_navigation')
@section('title', 'Analytics')

@push('styles')
<link rel="stylesheet" href="/css/front_page.css">
<link rel="stylesheet" href="/css/navigator.css">
<link rel="stylesheet" href="/css/body.css">
<link rel="stylesheet" href="/css/riasec.css">
@endpush
@section('modal-contents')
@endsection

@section('content')
<div class="body-content">
    <div class="riasec-content">
        <p class="summary-code-label">RIASEC Summary</p>
        <div class="tag-container">
            <p class="tag-label">
                Realistic (Doer)
            </p>
            <p class="tag-explanation">
                Someone who likes to work mainly with their hands by making and fixing things, assembling or operating equipment. They sometimes prefer working outdoors and find joy in a career that performs various manual labor. The Realistic individual works well with tools, machines, and mechanical drawings. Valuing practical things they can see and touch, they often see themselves as practical, mechanical, and goal oriented.
            </p>
            <p class="tag-career-options">
                Good Career Options
            </p>
            <ul>
                <li class="tag-career">Agriculture</li>
                <li class="tag-career">Health Assistant</li>
                <li class="tag-career">Computers</li>
                <li class="tag-career">Construction</li>
                <li class="tag-career">Machinist</li>
                <li class="tag-career">Food and Hospitality</li>
                <li class="tag-career">Carpenter</li>
                <li class="tag-career">Electrician</li>
                <li class="tag-career">Pilot</li>
                <li class="tag-career">Mechanic</li>
                <li class="tag-career">Facilities manager</li>
            </ul>
            <p class="tag-link">Click here for more careers under Realistic <a href="https://www.onetonline.org/explore/interests/Realistic">https://www.onetonline.org/explore/interests/Realistic</a></p>
        </div>
        <div class="tag-container">
            <p class="tag-label">
                Investigative (Thinker)
            </p>
            <p class="tag-explanation">
                The most analytical of the six personalities, this detail oriented group loves to study and solve math or science-related problems. They aren’t skilled negotiators but work best in a career choice that lets them work with others who are grounded. They are precise, intellectual, and goal oriented people.
            </p>
            <p class="tag-career-options">
                Good Career Options
            </p>
            <ul>
                <li class="tag-career">Marine Biology</li>
                <li class="tag-career">Engineering</li>
                <li class="tag-career">Chemistry</li>
                <li class="tag-career">Zoology</li>
                <li class="tag-career">Medicine/Surgery</li>
                <li class="tag-career">Consumer Economics</li>
                <li class="tag-career">Psychology</li>
                <li class="tag-career">Biologist</li>
                <li class="tag-career">Mathematician</li>
                <li class="tag-career">Computer Programmer</li>
                <li class="tag-career">Surveyor</li>
                <li class="tag-career">Pharmacist</li>
            </ul>
            <p class="tag-link">Click here for more careers under Investigative <a href="https://www.onetonline.org/explore/interests/Investigative">https://www.onetonline.org/explore/interests/Investigative</a></p>
        </div>
        <div class="tag-container">
            <p class="tag-label">
                Artistic (Creator)
            </p>
            <p class="tag-explanation">
                This group of individuals values others who are expressive and independent with an openness to experience. They naturally admire the creative arts, including writing and music, and have high levels of creativity. They see themselves as expressive and original and prefer to avoid a career choice that requires highly ordered or repetitive activities. They enjoy working in groups, but only if they are allowed expressive freedom and are encouraged to share their ideas.
            </p>
            <p class="tag-career-options">
                Good Career Options
            </p>
            <ul>
                <li class="tag-career">Communications</li>
                <li class="tag-career">Graphic Designer</li>
                <li class="tag-career">Musician</li>
                <li class="tag-career">Book Editor</li>
                <li class="tag-career">Art Teacher</li>
                <li class="tag-career">Actor</li>
                <li class="tag-career">Cosmetology</li>
                <li class="tag-career">Fine and Performing Arts</li>
                <li class="tag-career">Photography</li>
                <li class="tag-career">Radio and TV</li>
                <li class="tag-career">Interior Design</li>
                <li class="tag-career">Architecture</li>
            </ul>
            <p class="tag-link">Click here for more careers under Artistic <a href="https://www.onetonline.org/explore/interests/Artistic">https://www.onetonline.org/explore/interests/Artistic</a></p>
        </div>
        <div class="tag-container">
            <p class="tag-label">
                Social (Helper)
            </p>
            <p class="tag-explanation">
                These people like to work with other people, rather than things. Particularly value providing services for others and enjoy a career choice that enables them to work closely with people.
            </p>
            <p class="tag-career-options">
                Good Career Options
            </p>
            <ul>
                <li class="tag-career">Counseling</li>
                <li class="tag-career">Nursing</li>
                <li class="tag-career">Physical Therapy</li>
                <li class="tag-career">Travel</li>
                <li class="tag-career">Advertising</li>
                <li class="tag-career">Public Relations</li>
                <li class="tag-career">Education</li>
                <li class="tag-career">Librarian</li>
                <li class="tag-career">Social Worker</li>
            </ul>
            <p class="tag-link">Click here for more careers under Social <a href="https://www.onetonline.org/explore/interests/Social">https://www.onetonline.org/explore/interests/Social</a></p>
        </div>
        <div class="tag-container">
            <p class="tag-label">
                Enterprising (Persuader)
            </p>
            <p class="tag-explanation">
                Enterprising are the people you want on your team when it comes to getting things done. They’re energetic, confident and assertive, and they don’t shy away from a challenge. They can be extroverted or introverted, but they all share some common traits: they’re ambitious, hard-working and willing to try new things.
            </p>
            <p class="tag-career-options">
                Good Career Options
            </p>
            <ul>
                <li class="tag-career">Fashion Merchandising</li>
                <li class="tag-career">Real Estate</li>
                <li class="tag-career">Marketing/Sales</li>
                <li class="tag-career">Law</li>
                <li class="tag-career">Political Science</li>
                <li class="tag-career">International Trade</li>
                <li class="tag-career">Banking/Finance</li>
                <li class="tag-career">Sales Manager</li>
            </ul>
            <p class="tag-link">Click here for more careers under Enterprising <a href="https://www.onetonline.org/explore/interests/Enterprising">https://www.onetonline.org/explore/interests/Enterprising</a></p>
        </div>
        <div class="tag-container">
            <p class="tag-label">
                Conventional (Organizer)
            </p>
            <p class="tag-explanation">
                A member of this group would prefer a career choice where they can work with numbers, records, or machines. They enjoy repetitive tasks done in an orderly fashion and like to avoid ambiguous activities. They see themselves as organized and good at following directions. They value success in business and enjoy working with other people. However, they do best in small, systematic groups where they know their responsibilities.
            </p>
            <p class="tag-career-options">
                Good Career Options
            </p>
            <ul>
                <li class="tag-career">Accounting</li>
                <li class="tag-career">Court Reporting</li>
                <li class="tag-career">Insurance</li>
                <li class="tag-career">Administration</li>
                <li class="tag-career">Medical Recordsy</li>
                <li class="tag-career">Banking</li>
                <li class="tag-career">Data Processing</li>
                <li class="tag-career">Bookkeeper</li>
                <li class="tag-career">Secretary</li>
                <li class="tag-career">Bank Teller</li>
                <li class="tag-career">Mail Carrier</li>
                <li class="tag-career">HR Consultant</li>
            </ul>
            <p class="tag-link">Click here for more careers under Conventional <a href="https://www.onetonline.org/explore/interests/Conventional">https://www.onetonline.org/explore/interests/Conventional</a></p>
        </div>
    </div>
</div>

@endsection

@if(session('success'))
<script>
    var message = "{{ session('success') }}";
    alert(message);
</script>
@endif