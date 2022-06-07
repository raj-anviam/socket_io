<template>
    <div class="container">

    <div class="row">

      <div class="sidebar col-md-4" style="background-color: #eee" v-for="user in users">
          <a href=""><li>{{ user.name }} (<span class="user-status-">offline</span>)</li></a>
            <span class="user-status-"></span>
          <hr>
      </div>
      
      <!-- <div class="chat col-md-8" >
        <div class="header">

          <button class="seen-message">seen</button>
          
          <p>{{ $receiver->first_name }} <br> <span class="user-status-{{ $receiver->id }}">offline</span></p>

          <span class="user-typing-{{ Auth::user()->id }}"></span>
          
          <hr>
        </div>
        <div class="messages">
          
        </div>
        <div class="input col-md-12">
          <input type="text" class="form-control text-message">
          <button class="btn btn-dark mt-4 send-message">Send</button>
        </div>
        
      </div> -->
    </div>
  
  </div>
</template>

<script>
import axios from 'axios'

    export default {
        data: () => {
            return {
                users: [
                  {
                    name: 'test'
                  }
                ],
            }
        },
        mounted() {
          axios.get('http://127.0.0.1:8001/api/users', {
                      headers: {
                        Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjVkYzc5ZWY2YzU5Y2E1MjIzOGY0NTM4NWFiZTFlNTBlMzM3ZTRlZjcyMThhNTRlZDgxZjVmYTAwNDE2NTBiYzY0ZDQ2MjExM2VjZTQ3YjQiLCJpYXQiOjE2NTQ2MTA5MTYuMDQ3MTI2LCJuYmYiOjE2NTQ2MTA5MTYuMDQ3MTMxLCJleHAiOjE2ODYxNDY5MTUuODg0ODgzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.jhwIPTThF-KycajHqQByK7JjMKvh8deCKuoU5cqwsEPy4aRLFBjYCPbMF_mV741OmNaTlOcAXO5Cv0rJkBzWNc2lbodtQBMe_Pmba4G0rfEgBMRAbLdp2qi8HPg2h1wGnPBm32y7OsHv5UbEmsqHhsXlNzcGsh-3EaF3etq87mR2etZtq2Tag0BtOYkPT4_edoditDlIMXCf9m4EIHia5ZgdO5xTcNjxgLOZTLYYQaF29KJEh-QTkKUU3cJiO2WPUKTV0LuwXcV5CRexpeK8BRf02kASxD8k4SiIrOl5_2MqEaxDEU23BPJTorTgz8hKQwherhHIJ0zPfo14No9R2FfTdOUFulHatca9I6npEemMq-A0iXsWZ3BtyHs-tuK69w2xteqzeTYPZrGjHoq0ztB7GV9aAkTAUFpJ_MtM50n0CycksCpkhTpLq4A6d3jGPuwADCtEE_m79C6Cfl0k_BQxP9h56eyX73jHtHxx5mKkQOvSKd5ovVqc7YK7fuIge7vTqF2pWGSiGVGfqpBEzPfvrK7FleXcyFhqG9KuS7XdT7PQlOqzzARFpVzYJsLsaIAHbsrvoRvrdDnifu2Jaw2YQZaTIQcOnHfSWGIn6T70p6uGXg92Gh5I-Vagkm8huuOF6G-ITQq6CQgOU70MkYgmFSkfCmvrjgczjh67Efw'
                      }
                      })
            .then(data => console.log(data))

            console.log('Component mounted.', this.users)
        }
    }
</script>
