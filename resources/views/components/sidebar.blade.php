<style>
  aside {
  width: 200px; /* Adjust width as needed */
  height: 100%; /* Adjust height as needed */
  background-color: #333435; /* Optional styling */
}
ul.space-y-12 {  /* Add a class to target this specific ul */
  padding-top: 17px; /* Adjust padding as needed */
  padding-bottom: 20px; /* Adjust padding as needed */
}
li {
  margin-bottom: 20px; /* Adjust margin as needed */
}
img {
  width: 130px; /* Adjust width as needed */
  height: 105px; /* Adjust height as needed */
  margin-right: 30px;
}
/* Added styles for hover effect */
li a:hover {
  background-color: #33C2FF; /* Adjust hover background color and opacity */
  color: #33C2FF; /* Change text color on hover (optional) */
  transition: background-color 0.2s ease-in-out; /* Add a smooth transition */
  padding: 10px 15px; /* Add padding to each list item */

}

.logout-button {
  background-color: #BD0000; /* Set background color to red */
  color: #fff; /* Set text color to white */
  border: none; /* Remove default button border */
  padding: 10px 20px; /* Add some padding */
  border-radius: 5px; /* Add rounded corners */
  width: 200px;
  position: absolute; /* Make the button absolute positioned */
  bottom: 10px; /* Position it 10px from the bottom */
  left: 0; /* Position it at the left edge of the aside */
}

</style>

<aside class="w-custom min-h-screen">
  <div class="p-4">
    <div class="flex items-center justify-center">
      <img src="{{ asset('images/logo.png') }}" alt="Logo">
    </div>

    <ul class="space-y-12 ">
      <li>
        <a href="{{ route('admin.dashboard') }}" class="mt-6 mb-6 flex items-center text-white hover:text-gray-800">
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('admin.users.index') }}" class="mt-6 mb-6 flex items-center text-white hover:text-gray-800 text-center">
          User
        </a>
      </li>
      <li>
        <a href="{{ route('admin.categories') }}" class="mt-6 mb-6 flex items-center text-white hover:text-gray-800 text-center">
          Categories
        </a>
      </li>
      <li>
        <a href="{{ route('admin.books') }}" class="mt-6 mb-6 flex items-center text-white hover:text-gray-800 text-center">
          Books
        </a>
      </li>
      <li>
        <a href="{{ route('admin.book_request') }}" class="mt-6 mb-6 flex items-center text-white hover:text-gray-800 text-center">
          Book Requests
        </a>
      </li>
      <li>
        <a href="{{ route('admin.pending-books') }}" class="mt-6 mb-6 flex items-center text-white hover:text-gray-800 text-center">
          Selling Requests
        </a>
      </li>
      <li>
        <a href="{{ route('admin.feedback.index') }}" class="mt-6 mb-6 flex items-center text-white hover:text-gray-800 text-center">
          Feedback
        </a>
      </li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="logout-button">
            Logout
          </button>
        </form>
      </li>
    </ul>
  </div>
</aside>