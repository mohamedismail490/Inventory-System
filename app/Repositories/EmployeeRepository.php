<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class EmployeeRepository
{

    public function getEmployees($filter = [])
    {
        if ($filter) {
            $filter = (object)$filter;
        }
        $employees = Employee::query()->latest('id');
        $params = [];
        if (isset($filter->search_txt) && !empty($filter->search_txt)) {
            $employees = $employees->where(function ($q) use ($filter) {
                $q->where('name', 'LIKE', '%' . $filter->search_txt . '%')
                    ->orWhere('email', 'LIKE', '%' . $filter->search_txt . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $filter->search_txt . '%')
                    ->orWhere('address', 'LIKE', '%' . $filter->search_txt . '%')
                    ->orWhere('salary', 'LIKE', '%' . $filter->search_txt . '%')
                    ->orWhere('nid', 'LIKE', '%' . $filter->search_txt . '%');
            });
            $params['search_txt'] = $filter->search_txt;
        }
        if (isset($filter->paginate)) {
            $employees = $employees->paginate($filter->paginate);
            $params['paginate'] = $filter->paginate;
            if (!empty($params)) {
                $employees = $employees->appends($params);
            }
            return $employees;
        }
        return $employees->get();
    }

    public function getEmployeeById($id)
    {
        return Employee::findOrFail($id);
    }

    public function getEmployeeSalaryById($id)
    {
        return Salary::with(['employee'])
            ->where('id', $id)
            ->firstOrFail();
    }

    public function getSalaryByEmployeeId($employeeId, $month, $year)
    {
        return Salary::where('employee_id', $employeeId)
            ->where('salary_month', $month)
            ->where('salary_year', $year)
            ->first();
    }

    public function createEmployee($request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'salary' => $request->salary,
                'joining_date' => Carbon::parse($request->joining_date)->format('Y-m-d'),
            ];
            if ($request->nid) {
                $data['nid'] = $request->nid;
            }
            if ($request->photo) {
                $data['photo'] = uploadPhoto($request->photo, 'employees');
            }
            Employee::create($data);
            DB::commit();
            return (object)[
                'status' => true,
                'message' => 'Employee has been Created Successfully',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return (object)[
                'status' => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function updateEmployee($id, $request)
    {
        $employee = $this->getEmployeeById($id);
        $oldPhoto = $employee->getRawOriginal('photo');
        try {
            DB::beginTransaction();
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'salary' => $request->salary,
                'joining_date' => Carbon::parse($request->joining_date)->format('Y-m-d'),
            ];
            if ($request->nid) {
                $data['nid'] = $request->nid;
            } else {
                $data['nid'] = null;
            }
            if ($request->newPhoto) {
                $data['photo'] = uploadPhoto($request->newPhoto, 'employees');
            }
            $employee->update($data);
            if ($request->newPhoto && !empty($oldPhoto)) {
                if (file_exists($oldPhoto)) {
                    unlink($oldPhoto);
                }
            }
            DB::commit();
            return (object)[
                'status' => true,
                'message' => 'Employee has been Updated Successfully',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return (object)[
                'status' => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function destroyEmployee($id)
    {
        $employee = $this->getEmployeeById($id);
        try {
            $photo = $employee->getRawOriginal('photo');
            DB::beginTransaction();
            $employee->delete();
            if (!empty($photo)) {
                if (file_exists($photo)) {
                    unlink($photo);
                }
            }
            DB::commit();
            return (object)[
                'status' => true,
                'message' => 'Employee has been Deleted Successfully'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return (object)[
                'status' => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }


    public function payEmployeeSalary($id, $request)
    {
        $employee = $this->getEmployeeById($id);
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $joiningYear = Carbon::parse($employee->joining_date)->format('Y');
        $joiningMonth = Carbon::parse($employee->joining_date)->format('m');
        $salaryMonth = Carbon::parse($request->salary_month)->format('m');
        if (($request->salary_year < $joiningYear) || (($request->salary_year == $joiningYear) && ($salaryMonth < $joiningMonth))) {
            return (object)[
                'status' => false,
                'message' => 'Employee ' . $employee->name . ' Joining Date is after your Selected Salary Date'
            ];
        }
        if (($request->salary_year == $currentYear) && ($salaryMonth > $currentMonth)) {
            return (object)[
                'status' => false,
                'message' => $request->salary_month . ' - ' . $request->salary_year . ' has not been come Yet!!!'
            ];
        }
        $existedSalary = $this->getSalaryByEmployeeId($id, $request->salary_month, $request->salary_year);
        if (!empty($existedSalary)) {
            return (object)[
                'status' => false,
                'message' => 'Employee: (' . $employee->name . ') Salary for ' . $request->salary_month . ' - ' . $request->salary_year . ' has already been Paid'
            ];
        }
        try {
            DB::beginTransaction();
            $data = [
                'employee_id' => $id,
                'amount' => $employee->salary,
                'salary_date' => Carbon::now()->format('Y-m-d'),
                'salary_month' => $request->salary_month,
                'salary_year' => $request->salary_year
            ];
            Salary::create($data);
            DB::commit();
            return (object)[
                'status' => true,
                'message' => 'Salary has been Paid Successfully',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return (object)[
                'status' => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }

    public function getMonthsSalaries()
    {
        $salaries = Salary::query()->latest('id')
            ->select('salary_month')
            ->groupBy('salary_month')
            ->get();
        return $salaries;
    }

    public function getSalariesByMonth($month, $filter = [])
    {
        if ($filter) {
            $filter = (object)$filter;
        }
        $salaries = Salary::query()->with(['employee'])->latest('id')
            ->where('salary_month', $month);
        $params = [];
        if (isset($filter->paginate)) {
            $salaries = $salaries->paginate($filter->paginate);
            $params['paginate'] = $filter->paginate;
            if (!empty($params)) {
                $salaries = $salaries->appends($params);
            }
            return $salaries;
        }

        return $salaries->get();
    }

    public function updateEmployeeSalary($id, $request)
    {
        $salary = $this->getEmployeeSalaryById($id);
        $employee = $this->getEmployeeById($salary->employee_id);
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $joiningYear = Carbon::parse($employee->joining_date)->format('Y');
        $joiningMonth = Carbon::parse($employee->joining_date)->format('m');
        $salaryMonth = Carbon::parse($request->salary_month)->format('m');
        if (($request->salary_year < $joiningYear) || (($request->salary_year == $joiningYear) && ($salaryMonth < $joiningMonth))) {
            return (object)[
                'status' => false,
                'message' => 'Employee ' . $employee->name . ' Joining Date is after your Selected Salary Date'
            ];
        }
        if (($request->salary_year == $currentYear) && ($salaryMonth > $currentMonth)) {
            return (object)[
                'status' => false,
                'message' => $request->salary_month . ' - ' . $request->salary_year . ' has not been come Yet!!!'
            ];
        }
        $existedSalary = $this->getSalaryByEmployeeId($salary->employee_id, $request->salary_month, $request->salary_year);
        if (!empty($existedSalary) && ($existedSalary -> id != $salary -> id)) {
            return (object)[
                'status' => false,
                'message' => 'Employee: (' . $employee->name . ') Salary for ' . $request->salary_month . ' - ' . $request->salary_year . ' has already been Paid'
            ];
        }
        try {
            DB::beginTransaction();
            $data = [
                'salary_month' => $request->salary_month,
                'salary_year'  => $request->salary_year
            ];
            $salary->update($data);
            DB::commit();
            return (object)[
                'status' => true,
                'message' => 'Salary has been Updated Successfully',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return (object)[
                'status' => false,
                'message' => 'Something wrong happened! Please, try again.'
            ];
        }
    }
}
