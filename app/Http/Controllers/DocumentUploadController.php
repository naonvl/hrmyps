<?php

namespace App\Http\Controllers;

use App\Models\DocumentUpload;
use App\Models\Document;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DocumentUploadController extends Controller
{

    public function index()
{
    if (\Auth::user()->can('Manage Document')) {
        if (\Auth::user()->type == 'company' || \Auth::user()->type == 'hr') {
            $documents = DocumentUpload::orderBy('id', 'desc')->get();
        } else {
            $documents = DocumentUpload::where('created_by', \Auth::user()->id)
                ->with('createdBy')
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('documentUpload.index', compact('documents'));
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    public function list(Request $request)
    {
        $limit = $request->get('length', 10);
        $start = $request->get('start', 0);
        $search = $request->input('search.value');
        $query = DocumentUpload::query();

        if (\Auth::user()->type != 'hr') {
            $query->where('created_by', \Auth::user()->creatorId());
        }

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }

        $documents = $query->orderBy('id', 'desc')->paginate($limit, ['*'], 'page', $start / $limit + 1);

        return response()->json([
            'draw' => $request->get('draw'),
            'recordsTotal' => $documents->total(),
            'recordsFiltered' => $documents->total(),
            'data' => $documents->items(),
        ]);
    }
    public function create()
    {
        if (\Auth::user()->can('Create Document')) {
            $documentUploads = DocumentUpload::with('documentType')->where('created_by', \Auth::user()->employee->id)->whereIn('type', function($query) {
                $query->select('id')->from('documents')->where('is_mandatory', true);
            })->where('status','!=' ,'rejected')->get()->pluck('documentType.id')->toArray();
            $types = Document::whereNotIn('id',$documentUploads)->get()->pluck('name', 'id');
            $types->prepend('Pilih Tipe', '0');

            return view('documentUpload.create', compact('types'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function approve(Request $request)
    {
        if (\Auth::user()->type == 'hr') {
            $documentUpload = DocumentUpload::find($request->id);
            $documentUpload->status= 'approved';
            $documentUpload->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => __('Permission denied.')]);
        }
    }

    public function reject(Request $request)
    {
        if (\Auth::user()->type == 'hr') {
            $documentUpload = DocumentUpload::find($request->id);
            $documentUpload->status= 'rejected';
            $documentUpload->notes= $request->notes;
            $documentUpload->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => __('Permission denied.')]);
        }
    }



    public function store(Request $request)
    {

        if (\Auth::user()->can('Create Document')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'type' => 'required|not_in:null',
                    'documents' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if (!empty($request->documents)) {

                $filenameWithExt = $request->file('documents')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('documents')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/documentUpload/';
                $image_path = $dir . $fileNameToStore;
                if (\File::exists($image_path)) {
                    \File::delete($image_path);
                }
                $url = '';
                $path = \Utility::upload_file($request, 'documents', $fileNameToStore, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $document              = new DocumentUpload();
            $document->name        = $request->name;
            $document->document    = !empty($request->documents) ? $fileNameToStore : '';
            $document->role        = 0;
            $document->status        = Document::find($request->type)->need_approval ? 'pending' : 'approved';
            $document->type        = $request->type;
            $document->description = $request->description;
            $document->created_by  = \Auth::user()->creatorId();
            $document->save();

            return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(DocumentUpload $DocumentUpload)
    {
        //
    }


    public function edit($id)
    {

        if (\Auth::user()->can('Edit Document')) {
            $roles = Role::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $roles->prepend('All', '0');

            $DocumentUpload = DocumentUpload::find($id);

            return view('documentUpload.edit', compact('roles', 'DocumentUpload'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Document')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'documents' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $roles = $request->role;

            $document = DocumentUpload::find($id);

            if (!empty($request->documents)) {

                $filenameWithExt = $request->file('documents')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('documents')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/documentUpload/';
                $image_path = $dir . $fileNameToStore;
                if (\File::exists($image_path)) {
                    \File::delete($image_path);
                }
                $url = '';
                $path = \Utility::upload_file($request, 'documents', $fileNameToStore, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }


            $document->name = $request->name;
            if (!empty($request->documents)) {
                $document->document = !empty($request->documents) ? $fileNameToStore : '';
            }

            $document->role        = $request->role;
            $document->description = $request->description;
            $document->created_by = \Auth::user()->creatorId();
            $document->save();

            return redirect()->route('document-upload.index')->with('success', __('Document successfully uploaded.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($id)
    {
        if (\Auth::user()->can('Delete Document')) {
            $document = DocumentUpload::find($id);
            if ($document->created_by == \Auth::user()->creatorId()) {
                $document->delete();

                $dir = storage_path('uploads/documentUpload/');

                if (!empty($document->document)) {
                    // unlink($dir . $document->document);
                }

                return redirect()->route('document-upload.index')->with('success', __('Document successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
