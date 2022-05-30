@extends('templates.admin')
@section('content')
    <h1>Users list</h1>
    <table class="table table-striped table-dark data-table" id="users-table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Created At</th>
                <th scope="col">Deleted At</th>
                <th scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>

        </tfoot>
    </table>

@endsection

@section('footer')
    @parent

    <script type="text/javascript">

        $(document).ready(function() {

            var datatable = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{route('admin.getUsers')}}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'user_role', name: 'role' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'deleted_at', name: 'deleted_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            })

            $('#users-table').on('click', '.ajax', function(evt) {
                evt.preventDefault();
                const isDelete = this.id.indexOf('delete') >= 0
                const msg = isDelete ? `Do you really want to delete this record?` : `Do you really want to restore this record?`;

                if(!confirm(msg)) {
                    return false;
                }

                var urlUsers = $(this).attr('href');

                var tr = this.parentNode.parentNode;

                $.ajax(urlUsers, {
                    method: isDelete ? 'DELETE' : 'PATCH',
                    data: {
                        '_token': '{{ csrf_token() }}'
                    },
                    complete: function(response) {
                        // alert(response.responseText);
                        if( response.responseText == 1 ) {
                            if(urlUsers.endsWith('hard=1')) {
                                tr.remove();
                            }
                            datatable.ajax.reload();
                            alert(`User ${ isDelete ? 'deleted' : 'restored'} correctly!`);
                            // tr.remove();
                        }
                        else {
                            alert('Problems contacting server.')
                        }
                    }
                })
            })
        });


    </script>
@endsection
