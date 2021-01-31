(function ($) {

    /**
     * Cache requested data
     */
    var localCache = {
        data: {},
        remove: function (user) {
            delete localCache.data[user];
        },
        get: function (user) {
            console.log('Getting in cache for user ' + user);
            return localCache.data[user];
        },
        set: function (user, cachedData, callback) {
            localCache.remove(user);
            localCache.data[user] = cachedData;
            if ($.isFunction(callback)) {
                callback(cachedData);
            }
        }
    };

    /**
     * Fetchs users from API endpoint
     */
    $.ajax({
        url: 'https://jsonplaceholder.typicode.com/users',
        success: function (data) {
            $.each(data, function (index, user) {
                $('#userTable tbody').append(addRow(user));
                localCache.set(user.id, user);
            });
        },
        error: function () {
            console.log("ERROR: Data can't be fetch");
        }
    });

    /**
     * Parces users data and adds rows to table
     */
    function addRow(user)
    {
        var row =
        "<tr id='user_" + user.id + "'>" +
            "<th scope='row'><a class='table-link' href='#'>" + user.id + "</a></th>" +
            "<td>";
        row += ( user.name ) ? "<a class='table-link' href='#'>" + user.name + "</a>" : '';
        row += "</td>" +
            "<td>";
        row += ( user.username ) ? "<a class='table-link' href='#'>" + user.username + "</a>" : '';
        row += "</td>" +
            "</tr>";
        return row;
    }

    /**
     * Uses retrived or cached data
     */
    function showUserDetails( data )
    {
        var address = data.address;
        var company = data.company;
        var details =
        "<div class='col-sm-6 col-xs-12 shadow-sm p-5 mb-5 bg-white rounded'>";
        details += ( data.name ) ? "<p><span>Name:</span> " + data.name + "</p>" : '';
        details += ( data.username ) ? "<p><span>Username:</span> " + data.username + "</p>" : '';
        details += ( data.email ) ? "<p><span>Email:</span> " + data.email + "</p>" : '';
        details += ( data.website ) ? "<p><span>Website:</span> " + data.website + "</p>" : '';
        details += ( data.phone ) ? "<p><span>Phone:</span> " + data.phone + "</p>" : '';
        details += "</div>" +
        "<div class='col-sm-6 col-xs-12 shadow-sm p-5 mb-5 bg-white rounded'>" +
            "<p><span>Address: </span></p>";
        details += ( address.suite || address.street ) ? "<label>" + address.suite + " " + address.street + "</label>" : '';
        details += ( address.city  ) ? "<label>" + address.city + "</label>" : '';
        details += ( address.zipcode ) ? "<label>" + address.zipcode + "</label>" : '';
        details += "<p><span>Company: </span></p>";
        details += ( company.name ) ? "<label>" + company.name + "</label>" : '';
        details += ( company.catchPhrase ) ? "<label>" + company.catchPhrase + "</label>" : '';
        details += "</div>";
        $('#userDetails').html(details);
    }

    /**
     * Fetchs user details data from API endpoint
     */
    $('#userTable').on('click', 'a.table-link', function () {
        var user = $(this).parent().parent().attr('id').split('_');
        showUserDetails(localCache.get(user[1]));
        scrollToResults();
    });

    /**
     * Scrolls to users details section
     */
    function scrollToResults()
    {
        $('html, body').animate({
            scrollTop: $("#userDetails").offset().top
        });
    }

})(jQuery);