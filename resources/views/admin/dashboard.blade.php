<x-admin-layout>
    <div class="container mt-4">
        <h2>Dashboard</h2>

        <div class="row">
            <div class="col-md-4 p-3">
                <div class="card">
                    <div class="card-header">New Book Requests</div>
                    <div class="card-body">
                        <p>Total Requests: {{ $totalNewBookRequests }}</p>
                        <p>Pending Requests: {{ $pendingNewBookRequests }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-3">
                <div class="card">
                    <div class="card-header">Feedback</div>
                    <div class="card-body">
                        <p>Total Feedbacks: {{ $totalFeedbacks }}</p>
                        <p>Pending Feedbacks: {{ $pendingFeedbacks }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-3">
                <div class="card">
                    <div class="card-header">Users</div>
                    <div class="card-body">
                        <p>Total Users: {{ $totalUsers }}</p>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-3">
                <div class="card">
                    <div class="card-header">Total Books</div>
                    <div class="card-body">
                        <p>Total Books: {{ $totalBooks }}</p>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-4 p-3">
                <div class="card">
                    <div class="card-header">Total Categories</div>
                    <div class="card-body">
                        <p>Total Categories: {{ $totalCategories }}</p>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
