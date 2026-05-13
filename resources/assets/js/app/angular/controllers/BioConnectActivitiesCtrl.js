function BioConnectActivitiesCtrl($scope, $filter, Activity, ActivityCategory) {
    this.selectedCategory = '';
    this.activity = new Activity({ date_published: new Date() })
    
    _this = this
    Activity.query(function(activities) {
        _this.activities = activities;
    });

    ActivityCategory.query(function(activity_categories) {
        _this.activity_categories = activity_categories;
    });

    this.toggleCategory = function(category) {
        this.selectedCategory = category
    }

    this.createActivity = function(activity) {
        _this = this
        activity.date_published = $filter('date')($("#activity_date_published").val(), 'yyyy-MM-dd')
        if (activity.id == undefined) {
            activity.$save(function(_activity) {
                _this.activities.push(_activity)
                _this.activity = new Activity({ date_published: new Date() })
                _this.selectedCategory = _activity.category
            })
        } else {
            Activity.update(activity, function(_activity){
                index = _this.activities.indexOf(activity)
                _this.activities.splice(index, 1)
                _this.activities.push(_activity)
                _this.selectedCategory = _activity.category
                _this.activity = new Activity({ date_published: new Date() })
            })
        }
    }

    this.deleteActivity = function(activity) {
        _this = this;
        var confirmDialog = confirm("Are you sure you wish to delete this activity?");
        if (confirmDialog == true) {
            category = activity.category
            Activity.delete({ id: activity.id }, function() {
                if ($filter('where')(_this.activities, { category: category }).length < 2) {
                    _this.selectedCategory = ''
                }
                index = _this.activities.indexOf(activity)
                _this.activities.splice(index, 1)
                _this.activity = new Activity({ date_published: new Date() })
            })
        }
    }

    this.editActivity = function(activity) {
        _this.activity = activity
        _this.activity.date_published = new Date(activity.date_published)
    }

    this.cancelActionActivity = function(_activity) {
        _this.activity = new Activity({ date_published: new Date() })
        _this.selectedCategory = _activity.category
    }
}
BioConnectActivitiesCtrl.$inject = ['$scope', '$filter', 'Activity', 'ActivityCategory'];

angular.module('AnewApp').controller('BioConnectActivitiesCtrl', BioConnectActivitiesCtrl);
