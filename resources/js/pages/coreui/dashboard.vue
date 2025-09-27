<template>
  <div>
    <div class="row g-3">
      <div class="col-xl-3 col-lg-4 col-md-6" v-for="card in cards" :key="card.title">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-1">
              <div class="text-muted small">{{ card.subtitle }}</div>
              <fa :icon="card.icon" />
            </div>
            <div class="h4 mb-0">{{ card.value }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <h5 class="card-title mb-3">Recent Activity</h5>
        <ul class="list-unstyled mb-0">
          <li v-for="(item, idx) in activity" :key="idx" class="py-2 border-bottom">
            {{ item }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoreUIDashboard',
  metaInfo () {
    return { title: 'Dashboard' }
  },
  data: () => ({
    cards: [
      { title: 'Users', subtitle: 'Total users', value: '1,248', icon: 'user' },
      { title: 'Sessions', subtitle: 'This week', value: '8,320', icon: 'eye' },
      { title: 'Conversions', subtitle: 'Rate', value: '3.1%', icon: 'eye-dropper' },
      { title: 'Revenue', subtitle: 'This month', value: '$12,450', icon: 'lock' }
    ],
    activity: [
      'User John created an order',
      'Payment received from ACME Inc.',
      'Profile updated by Mary',
      'New message from Support'
    ]
  })
}
</script>

