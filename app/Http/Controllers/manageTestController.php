<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\mttests;
use App\Models\ettests;
use App\Models\quizzes;
use App\Models\tftests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class manageTestController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');


        $mcq = quizzes::select(
            'quizzes.qzID as id',
            'quizzes.qzTitle as title',
            'quizzes.qzDescription as description',
            'quizzes.qzTotal as total',
            'quizzes.qzIsPublic as public',
            'quizzes.subjectID',
            'quizzes.created_at',
            'IsHidden',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'MCQ' as type"),
            DB::raw('(SELECT COUNT(*) FROM quizitems WHERE quizitems.qzID = quizzes.qzID) as itemCount')
        )
            ->where('qzIsPublic', 1)
            ->leftJoin('subjects', 'quizzes.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'quizzes.user_id', '=', 'users.id');

        $et = ettests::select(
            'ettests.etID as id',
            'ettests.etTitle as title',
            'ettests.etDescription as description',
            'ettests.etTotal as total',
            'ettests.etIsPublic as public',
            'ettests.subjectID',
            'ettests.created_at',
            'IsHidden',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'ET' as type"),
            DB::raw('(SELECT COUNT(*) FROM etitems WHERE etitems.etID = ettests.etID) as itemCount')
        )
            ->where('etIsPublic', 1)
            ->leftJoin('subjects', 'ettests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'ettests.user_id', '=', 'users.id');

        // dd($et);

        $mt = mttests::select(
            'mttests.mtID as id',
            'mttests.mtTitle as title',
            'mttests.mtDescription as description',
            'mttests.mtTotal as total',
            'mttests.mtIsPublic as public',
            'mttests.subjectID',
            'mttests.created_at',
            'IsHidden',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'MT' as type"),
            DB::raw('(SELECT COUNT(*) FROM mtitems WHERE mtitems.mtID = mttests.mtID) as itemCount')
        )
            ->where('mtIsPublic', 1)
            ->leftJoin('subjects', 'mttests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'mttests.user_id', '=', 'users.id');

        $tf = tftests::select(
            'tftests.tfID as id',
            'tftests.tfTitle as title',
            'tftests.tfDescription as description',
            'tftests.tfTotal as total',
            'tftests.tfIsPublic as public',
            'tftests.subjectID',
            'tftests.created_at',
            'IsHidden',
            'subjects.subjectName',
            'users.first_name',
            'users.last_name',
            'users.user_image',
            DB::raw("'TF' as type"),
            DB::raw('(SELECT COUNT(*) FROM tfitems WHERE tfitems.tfID = tftests.tfID) as itemCount')
        )
            ->where('tfIsPublic', 1)
            ->leftJoin('subjects', 'tftests.subjectID', '=', 'subjects.subjectID')
            ->leftJoin('users', 'tftests.user_id', '=', 'users.id');

        // $mtf = mtftests::select(
        //     'mtftests.mtfID as id',
        //     'mtftests.mtfTitle as title',
        //     'mtftests.mtfDescription as description',
        //     'mtftests.mtfTotal as total',
        //     'mtftests.mtfIsPublic as public',
        //     'mtftests.subjectID',
        //     'mtftests.created_at',
        //     'subjects.subjectName',
        //     'users.first_name',
        //     'users.last_name',
        //     'users.user_image',
        //     DB::raw("'MTF' as type"),
        //     DB::raw('(SELECT COUNT(*) FROM mtfitems WHERE mtfitems.mtfID = mtftests.mtfID) as itemCount')
        // )
        //     ->where('mtfIsPublic', 1)
        //     ->leftJoin('subjects', 'mtftests.subjectID', '=', 'subjects.subjectID')
        //     ->leftJoin('users', 'mtftests.user_id', '=', 'users.id');

        $search = $request->input('search');

        if (!empty($search)) {
            $mcq->where(function ($query) use ($search) {
                $query->where('quizzes.qzTitle', 'LIKE', "%$search%")
                    ->orWhere('quizzes.qzDescription', 'LIKE', "%$search%");
            });

            $et->where(function ($query) use ($search) {
                $query->where('ettests.etTitle', 'LIKE', "%$search%")
                    ->orWhere('ettests.etDescription', 'LIKE', "%$search%");
            });

            $mt->where(function ($query) use ($search) {
                $query->where('mttests.mtTitle', 'LIKE', "%$search%")
                    ->orWhere('mttests.mtDescription', 'LIKE', "%$search%");
            });

            $tf->where(function ($query) use ($search) {
                $query->where('tftests.tfTitle', 'LIKE', "%$search%")
                    ->orWhere('tftests.tfDescription', 'LIKE', "%$search%");
            });
        }
        $result = $mcq
            ->union($et)
            ->union($mt)
            ->union($tf);
        $result = $result
            ->paginate(13);
        // dd($result);
        $page = 'managetest';
        return view('admin.manage_tests.index', [
            'pageType' => $page,
            'tests' => $result,
            'searchInput' => $search,
        ]);
    }
    public function hide($type, $id)
    {
        if ($type == 'mcq') {
            $test = quizzes::find($id);
            $test->update([
                'IsHidden' => $test->IsHidden ? 0 : 1,
            ]);
        }
        if ($type == 'tf') {
            $test = tftests::find($id);
            $test->update([
                'IsHidden' => $test->IsHidden ? 0 : 1,
            ]);
        }
        if ($type == 'mt') {
            $test = mttests::find($id);
            $test->update([
                'IsHidden' => $test->IsHidden ? 0 : 1,
            ]);
        }
        if ($type == 'et') {
            $test = ettests::find($id);
            $test->update([
                'IsHidden' => $test->IsHidden ? 0 : 1,
            ]);
        }
        return back();
    }
}
